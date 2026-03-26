<?php
/**
 * The model file of excel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     excel
 * @link        https://www.zentao.net
 */
class excelBug extends bugModel
{
    /**
     * Set list value.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function setListValue($productID, $branch = 0)
    {
        $product      = $this->loadModel('product')->getByID($productID);
        $projects     = arrayUnion(array(''), $this->loadModel('product')->getProjectPairsByProduct($productID, $branch == 'all' ? '' : "0,$branch"));
        $executions   = $this->product->getExecutionPairsByProduct($productID, $branch == 'all' ? '' : "0,$branch", $params = 'nodeleted');
        $modules      = $this->loadModel('tree')->getOptionMenu($productID, 'bug', 0, $branch);
        $stories      = $this->loadModel('story')->getProductStories($productID, $branch);
        $builds       = $this->loadModel('build')->getBuildPairs($productID, $branch, 'noempty');
        $severityList = $this->lang->bug->severityList;
        $priList      = $this->lang->bug->priList;
        $typeList     = $this->lang->bug->typeList;
        $osList       = $this->lang->bug->osList;
        $browserList  = $this->lang->bug->browserList;

        unset($typeList['']);
        unset($executions['']);
        unset($typeList['designchange']);
        unset($typeList['newfeature']);
        unset($typeList['trackthings']);
        $executions[0] = '';

        foreach($projects as $id => $project) $projects[$id]       = "$project(#$id)";
        foreach($executions as $id => $execution) $executions[$id] = "$execution(#$id)";
        foreach($modules  as $id => $module)  $modules[$id]       .= "(#$id)";
        foreach($stories  as $id => $story)   $stories[$id]        = "$story->title(#$story->id)";
        foreach($builds as $id => $build)     $builds[$id]         = "$build(#$id)";

        if($product->type != 'normal')
        {
            $this->config->bug->export->listFields[] = 'branch';

            $branches = $this->loadModel('branch')->getPairs($product->id);
            foreach($branches as $id => $branch) $branches[$id] .= "(#$id)";

            $this->post->set('branchList', array_values($branches));
        }

        if($this->config->edition != 'open') $this->loadModel('workflowfield')->setFlowListValue('bug');

        $this->post->set('moduleList',    array_values($modules));
        $this->post->set('storyList',     array_values($stories));
        $this->post->set('projectList',   array_values($projects));
        $this->post->set('executionList', array_values($executions));
        $this->post->set('severityList', join(',', $severityList));
        $this->post->set('priList',      join(',', $priList));
        $this->post->set('typeList',     join(',', $typeList));
        $this->post->set('osList',       join(',', $osList));
        $this->post->set('browserList',  join(',', $browserList));
        $this->post->set('listStyle', $this->config->bug->export->listFields);
        $this->post->set('extraNum', 0);
        $this->post->set('product', $product->name);
        $this->post->set('buildList',$builds);
    }

    /**
     * Create from import.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function createFromImport($productID, $branch = 0)
    {
        $this->loadModel('action');
        $this->loadModel('story');
        $this->loadModel('file');
        $now    = helper::now();
        $branch = (int)$branch;
        $data   = fixer::input('post')->get();

        $this->app->loadClass('purifier', true);
        $purifierConfig = HTMLPurifier_Config::createDefault();
        $purifierConfig->set('Filter.YouTube', 1);
        $purifier = new HTMLPurifier($purifierConfig);

        if(!empty($_POST['id'])) $oldBugs = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($_POST['id'])->andWhere('product')->eq($productID)->fetchAll('id', false);

        $bugs              = array();
        $line              = 1;
        $storyVersionPairs = $this->story->getVersions($data->story);
        $extendFields      = array();

        if($this->config->edition != 'open')
        {
            $extendFields = $this->getFlowExtendFields();
            $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');

            foreach($extendFields as $extendField)
            {
                if(strpos(",$extendField->rules,", ",$notEmptyRule->id,") !== false)
                {
                    $this->config->task->create->requiredFields .= ',' . $extendField->field;
                }
            }
        }

        $executions   = array();
        $executionIDs = array();
        foreach($data->execution as $key => $execution)
        {
            if(empty($data->project[$key])) $executionIDs[] = $execution;
        }
        $executions = $this->loadModel('execution')->getByIdList($executionIDs);

        foreach($data->product as $key => $product)
        {
            $bugData = new stdclass();

            $project   = (int)$data->project[$key];
            $execution = (int)$data->execution[$key];

            $bugData->product     = $product;
            $bugData->branch      = isset($data->branch[$key]) ? (int)$data->branch[$key] : $branch;
            $bugData->module      = (int)$data->module[$key];
            $bugData->project     = $project ? $project : (isset($executions[$execution]) ? $executions[$execution]->project : 0);
            $bugData->execution   = $execution;
            $bugData->openedBuild = !empty($data->openedBuild[$key]) ? join(',', $data->openedBuild[$key]) : '';
            $bugData->title       = $data->title[$key];
            $bugData->steps       = nl2br($purifier->purify($this->post->steps[$key]));
            $bugData->steps       = str_replace('%7B', '{', $bugData->steps);
            $bugData->steps       = str_replace('%7D', '}', $bugData->steps);
            $bugData->story       = (int)$data->story[$key];
            $bugData->pri         = (int)$data->pri[$key];
            $bugData->deadline    = $data->deadline[$key];
            $bugData->type        = $data->type[$key];
            $bugData->severity    = (int)$data->severity[$key];
            $bugData->os          = !empty($data->os[$key])      ? join(',', $data->os[$key])      : '';
            $bugData->browser     = !empty($data->browser[$key]) ? join(',', $data->browser[$key]) : '';
            $bugData->keywords    = $data->keywords[$key];
            $bugData->notifyEmail = $data->notifyEmail[$key];
            $bugData->feedbackBy  = $data->feedbackBy[$key];

            foreach($extendFields as $extendField)
            {
                $dataArray = $_POST[$extendField->field];
                $bugData->{$extendField->field} = $dataArray[$key];
                if(is_array($bugData->{$extendField->field})) $bugData->{$extendField->field} = join(',', $bugData->{$extendField->field});

                $bugData->{$extendField->field} = htmlSpecialString($bugData->{$extendField->field});
            }

            if(isset($this->config->bug->create->requiredFields))
            {
                $requiredFields = explode(',', $this->config->bug->create->requiredFields);
                foreach($requiredFields as $requiredField)
                {
                    $requiredField = trim($requiredField);
                    if($requiredField != 'project' and empty($bugData->$requiredField)) dao::$errors[$requiredField][] = sprintf($this->lang->bug->noRequire, $line, $this->lang->bug->$requiredField);
                }
            }

            if($bugData->notifyEmail and !validater::checkEmail($bugData->notifyEmail)) dao::$errors['notifyEmail'][] = sprintf($this->lang->bug->errorEmail, $line);
            if($bugData->deadline and !validater::checkDate($bugData->deadline))        dao::$errors['deadline'][]    = sprintf($this->lang->bug->errorDeadline, $line);

            $bugs[$key] = $bugData;
            $line++;
        }
        if(dao::isError()) die(js::error(dao::getError()));

        foreach($bugs as $key => $bugData)
        {
            $bugID = 0;
            if(!empty($_POST['id'][$key]) and empty($_POST['insert']))
            {
                $bugID = $data->id[$key];
                if(!isset($oldBugs[$bugID])) $bugID = 0;
            }

            if($bugID)
            {
                if($bugData->story != $oldBugs[$bugID]->story) $bugData->storyVersion = zget($storyVersionPairs, $bugData->story, 1);
                $bugData->steps = str_replace('src="' . common::getSysURL() . '/', 'src="', $bugData->steps);

                $oldBug = (array)$oldBugs[$bugID];
                $newBug = (array)$bugData;
                $oldBug['steps'] = trim($this->file->excludeHtml($oldBug['steps'], 'noImg'));
                $newBug['steps'] = trim($this->file->excludeHtml($newBug['steps'], 'noImg'));
                $changes = common::createChanges((object)$oldBug, (object)$newBug);
                if(empty($changes)) continue;

                /* Ignore updating bugs for different products. */
                if($oldBug['product'] != $newBug['product']) continue;

                $bugData->lastEditedBy   = $this->app->user->account;
                $bugData->lastEditedDate = $now;
                $this->dao->update(TABLE_BUG)->data($bugData)->where('id')->eq($bugID)->autoCheck()->checkFlow()->exec();

                if(!dao::isError())
                {
                    $actionID = $this->action->create('bug', $bugID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            else
            {
                if($bugData->story) $bugData->storyVersion = zget($storyVersionPairs, $bugData->story, 1);
                $bugData->openedBy   = $this->app->user->account;
                $bugData->openedDate = $now;

                $this->dao->insert(TABLE_BUG)->data($bugData)->autoCheck()->checkFlow()->exec();

                if(!dao::isError())
                {
                    $bugID = $this->dao->lastInsertID();
                    $this->action->create('bug', $bugID, 'Opened');
                }
            }
        }

        if($this->post->isEndPage)
        {
            unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
        }
    }
}
