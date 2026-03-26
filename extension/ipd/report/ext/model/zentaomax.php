<?php
public function getProjectByType($projectID = 0, $type = 'stage')
{
    $projects = $this->dao->select('id, name, begin, end, PM')->from(TABLE_PROJECT)
        ->where('project')->eq($projectID)
        ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
        ->andWhere('deleted')->eq(0)
        ->fetchAll('id');

    foreach($projects as $id => $project)
    {
        $tasks = $this->dao->select('COUNT(1) AS tasks')->from(TABLE_TASK)->where('execution')->eq($id)->andWhere('deleted')->eq(0)->fetch();
        $project->tasks = $tasks->tasks;
    }

    return $projects;
}

public function getStaff($executionIdList)
{
    return $this->dao->select('count(distinct account) as count, execution')
        ->from(TABLE_EFFORT)
        ->where('objectType')->eq('task')
        ->andWhere('execution')->in($executionIdList)
        ->groupBy('execution')
        ->fetchAll('execution');
}

public function getPV($executionIdList)
{
    $pvList = $this->dao->select('execution,sum(estimate) as estimate')->from(TABLE_TASK)
        ->where('execution')->in($executionIdList)
        ->groupBy('execution')
        ->fetchPairs();
    return $pvList;
}

public function getEV($executionIdList)
{
    $evList = $this->dao->select('execution,sum(consumed) as consumed')->from(TABLE_TASK)
        ->where('execution')->in($executionIdList)
        ->andWhere('status')->ne('cancel')
        ->groupBy('execution')
        ->fetchPairs();
    return $evList;
}

public function getMeasReportByID($reportID)
{
    return $this->dao->select('*')->from(TABLE_PROGRAMREPORT)->where('id')->eq($reportID)->fetch();
}

public function getMeasReports($projectID = 0, $templateID = 0)
{
    return $this->dao->select('*')
        ->from(TABLE_PROGRAMREPORT)
        ->where('deleted')->eq(0)
        ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
        ->beginIF($templateID)->andWhere('template')->eq($templateID)->fi()
        ->fetchAll();
}

public function buildReportList($projectID = 0)
{
    $project = $this->dao->findByID($projectID)->from(TABLE_PROJECT)->fetch();
    if(common::hasPriv('report', 'customeredReport'))
    {
        $templates = $this->loadModel('measurement')->getTemplatePairs($project->model);
        foreach($templates as $templateID => $templateName)
        {
            $this->lang->reportList->program->lists[] = $templateName . "|report|customeredreport|program={$projectID}&templateID=$templateID";
        }
    }
    if(common::hasPriv('report', 'show'))
    {
        $reportList = $this->getReportList('cmmi');
        foreach($reportList as $report)
        {
            $name = json_decode($report->name, true);
            if(!is_array($name) or empty($name)) $name[$this->app->getClientLang()] = $report->name;
            if(empty($report->module)) continue;
            $reportName = !isset($name[$this->app->getClientLang()]) ? $name['en'] : $name[$this->app->getClientLang()];
            $reportName = $this->replace4Workflow($reportName);
            $this->lang->reportList->program->lists[] = $reportName . "|report|show|reportID={$report->id}&reportModule=program";
        }
    }
}

public function saveMeasReport($projectID = 0, $templateID = 0, $content = '')
{
    $report = fixer::input('post')
        ->add('project', $projectID)
        ->add('template', $templateID)
        ->add('createdBy', $this->app->user->account)
        ->add('createdDate', helper::now())
        ->remove('parseContent, saveReport')
        ->get();
    $report->params  = json_encode($report->params);
    $report->content = $content;

    $this->dao->insert(TABLE_PROGRAMREPORT)
        ->data($report)
        ->check('name', 'notempty')
        ->autocheck()
        ->exec();

    if(dao::isError()) return false;

    return $this->dao->lastInsertID();
}
