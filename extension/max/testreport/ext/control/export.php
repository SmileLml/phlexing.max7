<?php
helper::importControl('testreport');
class mytestreport extends testreport
{
    public function export($reportID)
    {
        if($_POST)
        {
            $report = $this->testreport->getById($reportID);
            $data   = fixer::input('post')->get();
            if(empty($data->fileName)) $this->send(array('result' => 'fail' , 'message' => $this->lang->testreport->fileNameNotEmpty));

            if($data->fileType == 'word')
            {
                $this->exportWord($reportID);
            }
            elseif($data->fileType == 'html')
            {
                $jqueryCode = "$(function(){\n";
                foreach($_POST as $chart => $base64)
                {
                    if(strpos($chart, 'chart') === false) continue;
                    $jqueryCode .= "$('#$chart').find('canvas').replaceWith(\"<image src='$base64' />\");\n";
                }
                $jqueryCode .= "})\n";
                $this->session->set('notHead', true);
                $this->config->webRoot = getWebRoot(true);
                $output = $this->fetch('testreport', 'view', array('reportID' => (int)$reportID));
                $sysURL = common::getSysURL();
                $output = str_replace('<img src="', '<img src="' . $sysURL, $output);
                $output = preg_replace('/<i[^>]*>(.*?)<\/i>/s', '', $output);
                $this->session->set('notHead', false);
                $css    = '<style>' . $this->getCSS('testreport', 'export') . '</style>';
                $css   .= '<style>' . file_get_contents($this->app->getWwwRoot() . 'js/zui3/zui.zentao.css') . '</style>';
                $css   .= "<style>#header{display: none}</style>";
                $js     = '<script>' . $this->getJS('testreport', 'export') . $jqueryCode . '</script>';
                /* Get zui zentao js. */
                $jsFile = $this->app->getWwwRoot() . 'js/zui3/zui.zentao.js';
                $jquery = '<script>' . file_get_contents($jsFile) . '</script>';
                /* Get echarts js. */
                $jsFile = $this->app->getWwwRoot() . 'js/echarts/echarts.common.min.js';
                $jquery .= '<script>' . file_get_contents($jsFile) . '</script>';
                $jquery .= '<script>const exportNotice = "' . $this->lang->testreport->exportNotice . '";</script>';
                $content = "<!DOCTYPE html>\n<html lang='zh-cn'>\n<head>\n<meta charset='utf-8'>\n<title>{$report->title}</title>\n$jquery\n$css\n$js\n</head>\n<body onload='appendNotice()'>\n<h1>{$report->title}</h1>\n$output\n</body></html>";
                $this->fetch('file',  'sendDownHeader', array('fileName' => $data->fileName, 'fileType' => $data->fileType, 'content' =>$content));
            }
        }

        $this->display();
    }

    public function exportWord($reportID)
    {
        $report = $this->dao->select('*')->from(TABLE_TESTREPORT)->where('id')->eq($reportID)->fetch();
        $report->files = $this->loadModel('file')->getByObject('testreport', $reportID);

        $project = $this->project->getById($report->project);

        $project->goal = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->eq($report->execution)->fetch('desc');

        $stories = $report->stories ? $this->story->getByList($report->stories) : array();

        $tasks   = $report->tasks ? $this->testtask->getByList(explode(',', $report->tasks)) : array();
        $builds  = $report->builds ? $this->build->getByList(explode(',', $report->builds)) : array();
        $cases   = $this->testreport->getTaskCases($tasks, $report->begin, $report->end, $report->cases);
        $bugs    = $report->bugs ? $this->bug->getByIdList($report->bugs) : array();
        list($bugInfo, $bugSummary) = $this->testreportZen->buildReportBugData($tasks, array($report->product), $report->begin, $report->end, $builds);

        $storySummary = $this->product->summary($stories);
        $caseSummary  = $this->testreport->getResultSummary($tasks, $cases, $report->begin, $report->end);

        $legacyBugs = $bugSummary['legacyBugs'];
        unset($bugSummary['legacyBugs']);

        $projectProfile  = $storySummary . "<br />";
        $projectProfile .= sprintf($this->lang->testreport->buildSummary, empty($builds) ? 1 : count($builds)) . $caseSummary . "<br />";
        $projectProfile .= sprintf($this->lang->testreport->bugSummary, $bugSummary['foundBugs'], count($legacyBugs), $bugSummary['activatedBugs'], $bugSummary['countBugByTask'], $bugSummary['bugConfirmedRate'] . '%', $bugSummary['bugCreateByCaseRate'] . '%');
        unset($bugSummary['countBugByTask']);
        unset($bugSummary['bugConfirmedRate']);
        unset($bugSummary['bugCreateByCaseRate']);
        unset($bugSummary['foundBugs']);

        foreach($bugInfo as $infoKey => $infoValue)
        {
            if($infoKey == 'bugStageGroups')
            {
                $data = array();
                foreach($infoValue as $pri => $stageBugs)
                {
                    $priInfo = new stdclass();
                    $priInfo->name      = $pri == 0 ? $this->lang->null : $this->lang->bug->priList[$pri];
                    $priInfo->generated = $stageBugs['generated'];
                    $priInfo->legacy    = $stageBugs['legacy'];
                    $priInfo->resolved  = $stageBugs['resolved'];
                    $data[] = $priInfo;
                }
                $bugInfo[$infoKey] = $data;
            }
            elseif($infoKey == 'bugHandleGroups')
            {
                $data = array();
                if(!empty($infoValue['generated']))
                {
                    foreach($infoValue['generated'] as $date => $count)
                    {
                        $todayBugs = new stdclass();
                        $todayBugs->name      = $date;
                        $todayBugs->generated = $count;
                        $todayBugs->legacy    = $infoValue['legacy'][$date];
                        $todayBugs->resolved  = $infoValue['resolved'][$date];
                        $data[] = $todayBugs;
                    }
                }
                $bugInfo[$infoKey] = $data;
            }
            else
            {
                $sum = 0;
                foreach($infoValue as $value) $sum += $value->value;

                $list = $infoValue;
                if($infoKey == 'bugSeverityGroups')   $list = $this->lang->bug->severityList;
                if($infoKey == 'bugStatusGroups')     $list = $this->lang->bug->statusList;
                if($infoKey == 'bugResolutionGroups') $list = $this->lang->bug->resolutionList;
                foreach($list as $listKey => $listValue)
                {
                    if(!isset($infoValue[$listKey]))
                    {
                        $infoValue[$listKey] = new stdclass();
                        $infoValue[$listKey]->name  = $listValue;
                        $infoValue[$listKey]->value = 0;
                    }
                    if(empty($infoValue[$listKey]->name) and empty($infoValue[$listKey]->value))
                    {
                        unset($infoValue[$listKey]);
                        continue;
                    }
                    $infoValue[$listKey]->percent = $sum == 0 ? '0' : round($infoValue[$listKey]->value / $sum, 2);
                }
                $bugInfo[$infoKey] = $infoValue;
            }
        }

        if($report->objectType == 'testtask')
        {
            $this->setChartDatas($report->objectID);
        }
        elseif($tasks)
        {
            foreach($tasks as $task) $this->setChartDatas($task->id);
        }

        $this->post->set('charts', $this->view->charts);
        $this->post->set('datas', $this->view->datas);

        $this->post->set('report', $report);
        $this->post->set('project', $project);
        $this->post->set('stories', $stories);
        $this->post->set('bugs', $bugs);
        $this->post->set('builds', $builds);
        $this->post->set('cases', $cases);
        $this->post->set('projectProfile', $projectProfile);
        $this->post->set('legacyBugs', $legacyBugs);
        $this->post->set('bugInfo', $bugInfo);
        $this->post->set('kind', 'testreport');

        $this->fetch('file', 'exporttestreport', $_POST);
    }
}
