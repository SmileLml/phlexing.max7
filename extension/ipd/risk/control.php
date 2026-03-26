<?php
/**
 * The control file of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     risk
 * @version     $Id: control.php 5107 2020-09-04 09:06:12Z lyc $
 * @link        http://www.zentao.net
 */
class risk extends control
{
    public function commonAction($projectID, $from = 'project')
    {
        if($from == 'project')
        {
            $this->loadModel($from)->setMenu($projectID);
            $this->view->projectID = $projectID;
        }
        if($from == 'execution')
        {
            $this->loadModel($from)->setMenu($projectID);
            $this->executions = $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
            if(!$this->executions and $this->app->getViewType() != 'mhtml') $this->locate($this->createLink('execution', 'create'));
            $execution = $this->loadModel('execution')->getByID($projectID);
            $this->view->executionID = $projectID;
        }
    }

    /**
     * Browse risks.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $from = 'project', $browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->commonAction($projectID, $from);
        $uri = $this->app->getURI(true);
        $this->session->set('riskList', $uri, $this->app->tab);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('risk', 'browse', "projectID=$projectID&from=$from&browseType=bysearch&queryID=myQueryID");
        $this->risk->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->loadModel('custom');
        $risks = $this->risk->getList($projectID, $browseType, $param, $orderBy, $pager);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'risk');

        foreach($risks as $risk) $risk->relatedObject = $this->custom->getRelatedObjectList($risk->id, 'risk', 'byRelation', true);

        $relatedObjectList = $this->loadModel('custom')->getRelatedObjectList(array_keys($risks), 'risk', 'byRelation', true);
        foreach($risks as $risk) $risk->relatedObject = zget($relatedObjectList, $risk->id, 0);

        $this->view->title      = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->browse;
        $this->view->position[] = $this->lang->risk->browse;
        $this->view->risks      = $risks;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->projectID  = $projectID;
        $this->view->object     = $this->loadModel($from)->getByID($projectID);
        $this->view->pager      = $pager;
        $this->view->from       = $from;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->approvers  = $this->loadModel('assetlib')->getApproveUsers('risk');
        $this->view->libs       = $this->assetlib->getPairs('risk');

        $this->display();
    }

    /**
     * Get response information of a modal page
     *
     * @param  int  $projectID
     * @access public
     * @return void
     */
    public function responseModal($from)
    {
        $response['result']     = 'success';
        $response['message']    = $this->lang->saveSuccess;
        $response['closeModal'] = true;
        $response['load']       = true;

        return $this->send($response);
    }

    /**
     * 创建一个风险。
     * Create a risk.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function create($projectID = 0, $from = 'project')
    {
        $this->commonAction($projectID, $from);

        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $projectID = $execution->project;
            $this->view->executionID = $execution->id;
        }

        if($_POST)
        {
            $riskID = $this->risk->create($projectID);

            if(!$riskID) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('risk', $riskID, 'Opened');

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->session->riskList, 'id' => $riskID));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->create;
        $this->view->executions = $this->loadModel('execution')->getPairs($projectID, 'all', 'leaf');
        $this->view->projectID  = $projectID;
        $this->view->project    = $this->loadModel('project')->fetchByID($projectID);
        $this->view->users      = $this->project->getTeamMemberPairs((int)$projectID);

        $this->display();
    }

    /**
     * Edit a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function edit($riskID, $from = 'project')
    {
        $risk = $this->risk->getById($riskID);
        if($from == 'project') $this->commonAction($risk->project, $from);
        if($from == 'execution') $this->commonAction($risk->execution, $from);

        if($_POST)
        {
            $changes = $this->risk->update($riskID);

            if(dao::isError())
            {
                if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => dao::getError()));
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $this->loadModel('action');
            if(!empty($changes))
            {
                $actionID = $this->action->create('risk', $riskID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "riskID=$riskID&from=$from")));
        }

        $this->view->title       = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->edit;
        $this->view->projectList = $this->loadModel('project')->getPairsByModel('all');
        $this->view->risk        = $risk;
        $this->view->users       = $this->loadModel('project')->getTeamMemberPairs((int)$risk->project);
        $this->view->executions  = $this->loadModel('execution')->getPairs((int)$risk->project, 'all', 'leaf');
        $this->view->project     = $this->project->fetchByID((int)$risk->project);

        $this->display();
    }

    /**
     * View a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function view($riskID, $from = 'project')
    {
        $riskID = (int)$riskID;
        $risk   = $this->risk->getById($riskID);
        if(empty($risk))
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => '404 Not found'));
            return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('project', 'browse'))));
        }
        if($from == 'project') $this->commonAction($risk->project, $from);
        if($from == 'execution') $this->commonAction($risk->execution, $from);

        $this->view->title          = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->view;
        $this->view->risk           = $risk;
        $this->view->from           = $from;
        $this->view->actions        = $this->loadModel('action')->getList('risk', $riskID);
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->approvers      = $this->loadModel('assetlib')->getApproveUsers('risk');
        $this->view->libs           = $this->assetlib->getPairs('risk');
        $this->view->project        = $this->loadModel('project')->fetchByID($risk->project);
        $this->view->execution      = $this->loadModel('execution')->fetchByID($risk->execution);
        $this->view->preAndNext     = $this->loadModel('common')->getPreAndNextObject('risk', $riskID);
        $this->view->relatedObjects = $this->loadModel('custom')->getRelatedObjectList($risk->id, 'risk', 'byObject');

        $this->display();
    }

    /**
     * Batch create risks.
     *
     * @param  int    $projectID
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchCreate($projectID = 0, $from = 'project')
    {
        $this->commonAction($projectID, $from);

        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($projectID);
            $projectID = $execution->project;
            $this->view->executionID = $execution->id;
        }

        if($_POST)
        {
            $this->risk->batchCreate($projectID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->session->riskList));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->batchCreate;
        $this->view->executions = $this->loadModel('execution')->getPairs($projectID, 'all', 'leaf');
        $this->view->users      = $this->loadModel('project')->getTeamMemberPairs($projectID);
        $this->view->project    = $this->project->fetchByID($projectID);

        $this->display();
    }

    /**
     * Import from library.
     *
     * @param  int    $objectID
     * @param  int    $libID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function importFromLib($objectID, $from = 'project', $libID = 0, $orderBy = 'id_desc', $browseType = 'all', $queryID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->commonAction($objectID, $from);
        $browseType = strtolower($browseType);
        $queryID    = (int)$queryID;

        $projectID = $objectID;

        if($from == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($objectID);
            $projectID = $execution->project;
        }
        $executionID = isset($execution) ? $execution->id : 0;

        if($_POST)
        {
            $this->risk->importFromLib($projectID, $executionID);
            if(dao::isError()) return $this->sendError(array('message' => dao::getError()));
            $this->sendSuccess(array('load' => true));
        }

        $libraries = $this->loadModel('assetlib')->getPairs('risk');
        if(empty($libraries)) $this->sendError($this->lang->assetlib->noLibrary, $this->session->riskList);
        if(empty($libID) or !isset($libraries[$libID])) $libID = key($libraries);

        /* Build the search form. */
        $actionURL = $this->createLink('risk', 'importFromLib', "projectID=$objectID&from=$from&libID=$libID&orderBy=$orderBy&browseType=bysearch&queryID=myQueryID");
        $this->config->risk->search['module'] = 'importRisk';
        $this->config->risk->search['fields']['lib'] = $this->lang->assetlib->lib;
        $this->config->risk->search['params']['lib'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '', $libID => $libraries[$libID], 'all' => $this->lang->risk->allLib));
        $needUnsetFields = array('status','identifiedDate','resolution','plannedClosedDate','actualClosedDate','resolvedBy','activateBy','assignedTo','cancelBy','hangupBy','trackedBy');
        foreach($needUnsetFields as $fieldName) unset($this->config->risk->search['fields'][$fieldName]);
        $this->risk->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $risks = $this->risk->getNotImported($libraries, $libID, $projectID, $orderBy, $browseType, $queryID);
        $pager = pager::init(count($risks), $recPerPage, $pageID);
        $risks = array_chunk($risks, $pager->recPerPage);

        $this->view->title = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->importFromLib;

        $this->view->libraries  = $libraries;
        $this->view->libID      = $libID;
        $this->view->projectID  = $objectID;
        $this->view->risks      = empty($risks) ? $risks : $risks[$pageID - 1];
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->pager      = $pager;
        $this->view->from       = $from;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->queryID    = $queryID;

        $this->display();
    }

    /**
     * Delete a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function delete($riskID)
    {
        $this->risk->delete(TABLE_RISK, $riskID);

        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
        return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
    }

    /**
     * Track a risk.
     *
     * @param  int    $riskID
     * @param  string $before
     * @access public
     * @return void
     */
    public function track($riskID, $params = '')
    {
        $risk = $this->risk->getById($riskID);
        $this->loadModel('project')->setMenu((int)$risk->project);

        if($_POST)
        {
            $changes = array();
            if($this->post->isChange) $changes = $this->risk->track($riskID);

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action');
            if(!empty($changes) or $_POST['comment'])
            {
                $actionID = $this->action->create('risk', $riskID, 'Tracked', $_POST['comment']);
                $this->action->logHistory($actionID, $changes);
            }

            if($params)
            {
                $params = helper::safe64Decode($params);
                parse_str($params, $outputs);
                if($outputs && isset($outputs['module']) && isset($outputs['method']))
                {
                    $module = $outputs['module'];
                    $method = $outputs['method'];
                    $this->loadModel("$module");
                    if(method_exists($this->$module, $method)) $this->$module->$method($outputs);
                }
            }

            $isInModal = isInModal();
            if($isInModal) $response['closeModal'] = true;
            $response['load'] = $isInModal ? true : inlink('browse', "projectID=$risk->project");
            return $this->send($response);
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->track;
        $this->view->position[] = $this->lang->risk->track;

        $this->view->risk  = $risk;
        $this->view->users = $this->loadModel('user')->getPairs('noclosed');
        $this->display();
    }

    /**
     * Update assign of risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function assignTo($riskID)
    {
        $risk = $this->risk->getById($riskID);

        if($_POST)
        {
            $changes = $this->risk->assign($riskID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('risk', $riskID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            if(isInModal() || isonlybody()) return $this->sendSuccess(array('load' => true, 'closeModal' => true));
            return $this->sendSuccess(array('load' => $this->createLink('risk', 'view', "riskID=$riskID")));
        }

        $this->view->title = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->assignedTo;
        $this->view->risk  = $risk;
        $this->view->users = $this->loadModel('project')->getTeamMemberPairs((int)$risk->project);

        $this->display();
    }


    /**
     * Cancel a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function cancel($riskID)
    {
        $risk = $this->risk->getById($riskID);

        if($_POST)
        {
            $changes = $this->risk->cancel($riskID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('risk', $riskID, 'Canceled', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => 'success', 'load' => $this->createLink('risk', 'browse', "projectID=$risk->project")));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->cancel;
        $this->view->position[] = $this->lang->risk->cancel;

        $this->view->users = $this->loadModel('project')->getTeamMemberPairs((int)$risk->project);
        $this->view->risk  = $risk;
        $this->display();
    }

    /**
     * Close a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function close($riskID)
    {
        $risk = $this->risk->getById($riskID);

        if($_POST)
        {
            $changes = $this->risk->close($riskID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('risk', $riskID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => 'success', 'load' => $this->createLink('risk', 'browse', "projectID=$risk->project")));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->close;
        $this->view->position[] = $this->lang->risk->close;

        $this->view->users = $this->loadModel('project')->getTeamMemberPairs((int)$risk->project);
        $this->view->risk  = $risk;
        $this->display();
    }

    /**
     * Hangup a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function hangup($riskID)
    {
        $risk = $this->risk->getById($riskID);

        if($_POST)
        {
            $changes = $this->risk->hangup($riskID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('risk', $riskID, 'Hangup', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => 'success', 'load' => $this->createLink('risk', 'browse', "projectID=$risk->project")));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->hangup;
        $this->view->position[] = $this->lang->risk->hangup;

        $this->view->users = $this->loadModel('project')->getTeamMemberPairs((int)$risk->project);
        $this->view->risk  = $risk;
        $this->display();
    }

    /**
     * Activate a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function activate($riskID, $params = '')
    {
        $risk = $this->risk->getById($riskID);

        if($_POST)
        {
            $changes = $this->risk->activate($riskID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('risk', $riskID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            if($params)
            {
                $params = helper::safe64Decode($params);
                parse_str($params, $outputs);
                if($outputs && isset($outputs['module']) && isset($outputs['method']))
                {
                    $module = $outputs['module'];
                    $method = $outputs['method'];
                    $this->loadModel("$module");
                    if(method_exists($this->$module, $method)) $this->$module->$method($outputs);
                }
            }

            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => 'success', 'load' => $this->createLink('risk', 'browse', "projectID=$risk->project")));
        }

        $this->view->title      = $this->lang->risk->common . $this->lang->hyphen . $this->lang->risk->activate;
        $this->view->position[] = $this->lang->risk->activate;

        $this->view->users = $this->loadModel('project')->getTeamMemberPairs((int)$risk->project);
        $this->view->risk  = $risk;
        $this->display();
    }

    /**
     * Import risk to risk lib.
     *
     * @param  int    $riskID
     * @access public
     * @return void
     */
    public function importToLib($riskID)
    {
        $this->risk->importToLib($riskID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'load' => true));
    }

    /**
     * Batch import to lib.
     *
     * @access public
     * @return void
     */
    public function batchImportToLib()
    {
        $riskIdList = $this->post->riskIdList;
        $this->risk->importToLib($riskIdList);
        if(dao::isError()) return $this->sendError(array('message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'locate' => 'reload'));
    }

    /**
     * AJAX: return risks of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id
     * @param  string $status
     * @access public
     * @return void
     */
    public function ajaxGetUserRisks($userID = '', $id = '', $status = 'all')
    {
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $items = array();
        $risks = $this->risk->getUserRiskPairs($account, 0, $status);
        foreach($risks as $riskID => $riskTitle) $items[] = array('text' => $riskTitle, 'value' => $riskID);
        return print(json_encode(array('name' => $id ? "risks[{$id}]" : 'risk', 'items' => $items)));
    }

    /**
     * Ajax get project risks.
     *
     * @param  int    $projectID
     * @param  string $append
     * @access public
     * @return void
     */
    public function ajaxGetProjectRisks($projectID, $append = '')
    {
        $risks = $this->risk->getProjectRiskPairs($projectID, $append);
        $items = array();
        foreach($risks as $id => $name)
        {
            $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        }
        echo json_encode($items);
    }

    /**
     * Export risk.
     *
     * @param  string $objectID
     * @param  string $browseType
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($objectID, $browseType, $orderBy)
    {
        $object = $this->loadModel('project')->fetchByID($objectID);
        if($_POST)
        {
            $this->loadModel('file');
            $riskLang = $this->lang->risk;

            /* Create field lists. */
            $sort   = common::appendOrder($orderBy);
            $fields = explode(',', $this->config->risk->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($riskLang->$fieldName) ? $riskLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }
            if(empty($object->multiple)) unset($fields['execution']);

            /* Get risks. */
            $risks = $this->dao->select('*')->from(TABLE_RISK)
                ->where($this->session->riskQueryCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
                ->orderBy($sort)
                ->fetchAll('id', false);

            /* Get executions. */
            if(!empty($object->multiple)) $executions = $this->loadModel('execution')->getByIdList(helper::arrayColumn($risks, 'execution'), 'all');

            /* Get users. */
            $users = $this->loadModel('user')->getPairs('noletter');

            $data = array();
            foreach($risks as $risk)
            {
                $tmp = new stdClass();
                $tmp->id                = $risk->id;
                $tmp->source            = zget($this->lang->risk->sourceList, $risk->source, '');
                $tmp->name              = $risk->name;
                $tmp->execution         = isset($executions[$risk->execution]) ? $executions[$risk->execution]->name . "(#{$risk->execution})" : '';
                $tmp->category          = zget($this->lang->risk->categoryList, $risk->category, '');
                $tmp->strategy          = zget($this->lang->risk->strategyList, $risk->strategy, '');
                $tmp->status            = zget($this->lang->risk->statusList, $risk->status, '');
                $tmp->impact            = $risk->impact;
                $tmp->probability       = $risk->probability;
                $tmp->rate              = $risk->rate;
                $tmp->pri               = zget($this->lang->risk->priList, $risk->pri, '');
                $tmp->identifiedDate    = $risk->identifiedDate;
                $tmp->plannedClosedDate = $risk->plannedClosedDate;
                $tmp->actualClosedDate  = $risk->actualClosedDate;
                $tmp->assignedTo        = isset($users[$risk->assignedTo]) ? $users[$risk->assignedTo] . "(#{$risk->assignedTo})" : '';

                if($this->post->fileType == 'csv')
                {
                    $risk->prevention = htmlspecialchars_decode($risk->prevention);
                    $risk->prevention = str_replace("<br />", "\n", $risk->prevention);
                    $tmp->prevention = str_replace('"', '""', $risk->prevention);

                    $risk->remedy = htmlspecialchars_decode($risk->remedy);
                    $risk->remedy = str_replace("<br />", "\n", $risk->remedy);
                    $tmp->remedy = str_replace('"', '""', $risk->remedy);

                    $risk->resolution = htmlspecialchars_decode($risk->resolution);
                    $risk->resolution = str_replace("<br />", "\n", $risk->resolution);
                    $tmp->resolution = str_replace('"', '""', $risk->resolution);
                }
                else
                {
                    $tmp->prevention        = $risk->prevention;
                    $tmp->remedy            = $risk->remedy;
                    $tmp->resolution        = $risk->resolution;
                }

                $data[] = $tmp;
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $data);
            $this->post->set('kind', 'risk');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $fileName = zget($object, 'name',  '') . $this->lang->dash . zget($this->lang->risk->featureBar['browse'], $browseType, '') . $this->lang->risk->common;

        $this->view->fileName = $fileName;
        $this->display();
    }
}
