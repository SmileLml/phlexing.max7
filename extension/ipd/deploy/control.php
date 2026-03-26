<?php
/**
 * The control file of deploy of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     deploy
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class deploy extends control
{
    /**
     * 上线申请页面。
     * Browse deployments.
     *
     * @param  int    $productID
     * @param  string $status
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $status = 'wait', $param = 0, $orderBy = 'name_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $plans = $this->deploy->getList($productID, $status, $param, $orderBy, $pager);
        foreach($plans as $plan)
        {
            if(!isset($plan->product)) $plan->product = '';
            if(!isset($plan->system)) $plan->system = '';
            $plan->product = trim($plan->product, ',');
            $plan->system  = trim($plan->system, ',');
            $plan->desc    = strip_tags($plan->desc);
        }

        $users    = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $systems  = $this->loadModel('system')->getPairs();

        $actionURL = inLink('browse', "productID=$productID&status=bySearch&param=myQueryID");
        $this->config->deploy->search['actionURL'] = $actionURL;
        $this->config->deploy->search['queryID']   = (int)$param;

        $this->config->deploy->search['params']['product']['values']    = $products;
        $this->config->deploy->search['params']['owner']['values']      = $users;
        $this->config->deploy->search['params']['members']['values']    = $users;
        $this->config->deploy->search['params']['createdBy']['values']  = $users;
        $this->config->deploy->search['params']['reviewedBy']['values'] = $users;
        $this->config->deploy->search['params']['system']['values']     = $systems;
        $this->loadModel('search')->setSearchParams($this->config->deploy->search);

        $this->view->title     = $this->lang->deploy->browse;
        $this->view->productID = $productID;
        $this->view->plans     = $plans;
        $this->view->status    = $status;
        $this->view->param     = $param;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;
        $this->view->users     = $users;
        $this->view->products  = $products;
        $this->view->systems   = $systems;
        $this->display();
    }

    /**
     * Create deployment.
     *
     * @param  int    $product
     * @access public
     * @return void
     */
    public function create($product = 0)
    {
        if($_POST)
        {
            $form = form::data($this->config->deploy->form->create)
                ->add('createdBy', $this->app->user->account)
                ->add('status', 'wait')
                ->skipSpecial('desc')
                ->get();
            $this->deployZen->checkFormData($form);
            if(dao::isError()) return $this->sendError(dao::getError());

            $deployID = $this->deploy->create($form);
            if(dao::isError()) $this->sendError(dao::getError());

            $this->loadModel('action')->create('deploy', $deployID, 'Created');
            return $this->sendSuccess(array('load' => helper::createLink('deploy', 'browse')));
        }

        $this->view->title    = $this->lang->deploy->create;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->view->product  = $product;
        $this->view->products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $this->view->releases = $product ? $this->loadModel('release')->getPairsByProduct($product) : array();
        $this->view->hosts    = $this->loadModel('host')->getPairs('', 'online');

        $this->display();
    }

    /**
     * Edit the deployment.
     *
     * @param  int   $deployID
     * @param  bool  $comment
     * @access public
     * @return void
     */
    public function edit($deployID, $comment = false)
    {
        if($_POST)
        {
            $form = form::data($this->config->deploy->form->edit)->get();

            $this->deployZen->checkFormData($form);
            if(dao::isError()) return $this->sendError(dao::getError());

            if(!$comment)
            {
                $changes = $this->deploy->update($deployID, $form);
                if(dao::isError()) return $this->sendError(dao::getError());
            }

            if(!empty($changes) || $this->post->comment)
            {
                $action   = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->loadModel('action')->create('deploy', $deployID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('load' => true));
        }

        $deploy = $this->deploy->getById($deployID);
        $productIdList = array();
        foreach($deploy->products as $deployProduct) $productIdList[$deployProduct->product] = $deployProduct->product;

        $releaseGroup = $this->dao->select('*')->from(TABLE_RELEASE)->where('product')->in($productIdList)->andWhere('deleted')->eq(0)->fetchGroup('product', 'id');
        $systemList   = $this->loadModel('system')->getPairs();
        foreach($releaseGroup as $product => $releases)
        {
            foreach($releases as $id => $release) $releaseGroup[$product][$id] = zget($systemList, $release->system, '') . $release->name;
        }

        $this->view->title        = $this->lang->deploy->edit;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed', $deploy->owner . ',' . $deploy->members);
        $this->view->deploy       = $deploy;
        $this->view->products     = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $this->view->linkProducts = $productIdList;
        $this->view->releaseGroup = $releaseGroup;
        $this->view->hosts        = $this->loadModel('host')->getPairs('', 'online');

        $this->display();
    }

    /**
     * Delete the deployment.
     *
     * @param  int    $delployID
     * @access public
     * @return void
     * @param int $deployID
     */
    public function delete($deployID)
    {
        $this->deploy->delete(TABLE_DEPLOY, $deployID);

        if(dao::isError()) return $this->sendError(dao::getError());
        return $this->sendSuccess(array('load' => inLink('browse'), 'message' => $this->lang->deleteSuccess));
    }

    /**
     * Activate the deployment.
     *
     * @param  int    $deployID
     * @access public
     * @return void
     */
    public function activate($deployID)
    {
        if($_POST)
        {
            $form = form::data($this->config->deploy->form->activate)->get();

            $changes = $this->deploy->changeStatus($deployID, 'activate', $form);
            if(dao::isError()) return $this->sendError(dao::getError());

            $actionID = $this->loadModel('action')->create('deploy', $deployID, "canceled", $this->post->comment);
            if($changes) $this->action->logHistory($actionID, $changes);

            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title  = $this->lang->deploy->activate;
        $this->view->deploy = $this->deploy->getById($deployID);
        $this->display();
    }

    /**
     * 完成一个上线申请。
     * Finish a deployment.
     *
     * @param  int    $deployID
     * @access public
     * @return void
     *
     */
    public function finish($deployID)
    {
        if($_POST)
        {
            $formData = form::data($this->config->deploy->form->finish)->get();

            $changes = $this->deploy->changeStatus($deployID, 'finish', $formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            $actionID = $this->loadModel('action')->create('deploy', $deployID, "finished", $this->post->desc);
            if($changes) $this->action->logHistory($actionID, $changes);

            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title  = $this->lang->deploy->finish;
        $this->view->deploy = $this->deploy->getById($deployID);
        $this->view->users  = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->display();
    }

    /**
     * View the deployment.
     *
     * @param  int    $deployID
     * @access public
     * @return void
     */
    public function view($deployID)
    {
        $deployID = (int)$deployID;
        $deploy   = $this->deploy->getById($deployID);
        if(!$deploy) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('deploy', 'browse'))));

        $this->session->set('stepList', $this->app->getURI(true));

        $productIdList = $releaseIdList = array();
        foreach($deploy->products as $deployProduct)
        {
            $productIdList[$deployProduct->product] = $deployProduct->product;
            $releaseIdList[$deployProduct->release] = $deployProduct->release;
        }

        $releaseList = $this->loadModel('release')->getListByCondition($releaseIdList);
        $systemList  = $this->loadModel('system')->getPairs();

        $releases = array();
        foreach($releaseList as $releaseID => $release)
        {
            if($release->system) $releases[$releaseID] = $systemList[$release->system] . $release->name;
        }

        $this->view->title    = $this->lang->deploy->view;
        $this->view->deploy   = $deploy;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->view->products = $this->loadModel('product')->getByIdList($productIdList);
        $this->view->releases = $releases;
        $this->view->actions  = $this->loadModel('action')->getList('deploy', $deployID);
        $this->display();
    }

    /**
     * The cases of the deployment.
     *
     * @param int $deployID
     * @access public
     * @return void
     */
    public function cases($deployID)
    {
        $this->session->set('deployID', $deployID);
        $deploy = $this->deploy->getById($deployID);

        $cases = array();
        if($deploy->cases)
        {
            $cases   = $this->loadModel('testcase')->getByList(explode(',', $deploy->cases));
            $results = $this->testcase->getCaseResultsForExport(array_keys($cases));
            foreach($cases as $case)
            {
                $case->result = '';
                if(!empty($results[$case->id]))
                {
                    $result = current($results[$case->id]);
                    $case->lastResult = $result['result'];
                }
            }
        }

        $this->view->title  = $this->lang->deploy->cases;
        $this->view->deploy = $deploy;
        $this->view->users  = $this->loadModel('user')->getPairs('noletter|nodeleted|noclosed');
        $this->view->cases  = $cases;
        $this->display();
    }

    /**
     * Link cases.
     *
     * @param  int    $deployID
     * @param  string $type
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkCases($deployID, $type = 'all', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(!empty($_POST))
        {
            $this->deploy->linkCases($deployID);
            return dao::isError() ? $this->sendError(dao::getError()) : $this->sendSuccess(array('load' => helper::createLink('deploy', 'cases', "deploy=$deployID")));
        }

        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true));

        /* Get task and product id. */
        $deploy  = $this->deploy->getById($deployID);
        $productIdList = array();
        foreach($deploy->products as $deployProduct) $productIdList[$deployProduct->product] = $deployProduct->product;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $this->loadModel('testcase');
        $this->app->loadLang('testtask');
        unset($this->config->testcase->search['fields']['product']);
        unset($this->config->testcase->search['params']['product']);
        unset($this->config->testcase->search['fields']['branch']);
        unset($this->config->testcase->search['params']['branch']);
        unset($this->config->testcase->search['fields']['module']);
        unset($this->config->testcase->search['params']['module']);
        $this->config->testcase->search['actionURL'] = inlink('linkCases', "deployID=$deployID&type=bySearch&param=myQueryID");
        $this->config->testcase->search['queryID']   = $type == 'bySearch' ? $param : 0;
        $this->loadModel('search')->setSearchParams($this->config->testcase->search);

        $this->view->title      = $deploy->name . $this->lang->hyphen . $this->lang->deploy->linkCases;
        $this->view->position[] = html::a($this->session->deployList, $this->lang->deploy->common);
        $this->view->position[] = $this->view->title;

        /* Get cases. */
        $cases = $this->deploy->getLinkableCases($deploy, $productIdList, $type, $param, $pager);

        $suiteList = array();
        $this->loadModel('testsuite');
        foreach($productIdList as $productID) $suiteList = arrayUnion($suiteList, $this->testsuite->getSuites($productID));

        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->cases     = $cases;
        $this->view->deploy    = $deploy;
        $this->view->pager     = $pager;
        $this->view->type      = $type;
        $this->view->param     = $param;
        $this->view->suiteList = $suiteList;

        $this->display();
    }

    /**
     * Unlink cases.
     *
     * @param  int    $deployID
     * @param  int    $caseID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function unlinkCase($deployID, $caseID)
    {
        $deploy = $this->deploy->getById($deployID);
        $cases  = trim(str_replace(",$caseID,", ',', ",{$deploy->cases},"), ',');
        $this->dao->update(TABLE_DEPLOY)->set('cases')->eq($cases)->where('id')->eq((int)$deployID)->exec();

        return dao::isError() ? $this->sendError(dao::getError()) : $this->sendSuccess(array('message' => '', 'load' => true));
    }

    /**
     * Batch unlink cases.
     *
     * @param  int    $deployID
     * @access public
     * @return void
     */
    public function batchUnlinkCases($deployID)
    {
        if(isset($_POST['idList']))
        {
            $deploy = $this->deploy->getById($deployID);
            $cases  = "," . trim($deploy->cases, ',') . ",";

            $deletedList = fixer::input('post')->get('idList');
            foreach($deletedList as $caseID) $cases = str_replace(",$caseID,", ',', $cases);
            $this->dao->update(TABLE_DEPLOY)->set('cases')->eq(trim($cases, ','))->where('id')->eq((int)$deployID)->exec();
        }

        return $this->sendSuccess(array('message' => '', 'load' => true));
    }

    /**
     * The steps of the deployment.
     *
     * @param  int    $deployID
     * @access public
     * @return void
     * @param string $orderBy
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
     */
    public function steps($deployID, $orderBy = 'id_asc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->session->set('deployID', $deployID);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $deploy = $this->deploy->getById($deployID);
        $steps  = $this->deploy->getStepList($deployID, $orderBy, $pager);
        if($deploy->cases)
        {
            $this->app->loadLang('testtask');
            $stepGroups['cases'] = $this->loadModel('testcase')->getByList(explode(',', $deploy->cases));
            $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase');

            $this->view->results = $this->dao->select('*')->from(TABLE_TESTRESULT)->where('`case`')->in($deploy->cases)->andWhere('deploy')->eq($deployID)->orderBy('date')->fetchAll('case');
        }

        $this->view->title  = $this->lang->deploy->steps;
        $this->view->users  = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        $this->view->deploy = $deploy;
        $this->view->steps  = $steps;
        $this->view->pager  = $pager;
        $this->display();
    }

    /**
     * 管理上线步骤。
     * Manage step.
     *
     * @param  int    $deployID
     * @access public
     * @return void
     */
    public function manageStep($deployID)
    {
        $stepList = $this->deploy->getStepList($deployID, 'parent_asc');
        if($_POST)
        {
            $steps = form::data($this->config->deploy->form->manageStep)->get();
            $this->deploy->manageStep($deployID, $steps, $stepList);
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->sendSuccess(array('load' => inLink('steps', "deployID=$deployID")));
        }

        $this->view->deploy     = $this->deploy->getById($deployID);
        $this->view->title      = $this->lang->deploy->manageStep;
        $this->view->object     = $this->deploy->getById($deployID);
        $this->view->stepGroups = $this->deployZen->processSteps($stepList);
        $this->display();
    }

    /**
     * 完成一个上线步骤。
     * Finish step.
     *
     * @param  int    $deployID
     * @access public
     * @return void
     * @param int $stepID
     */
    public function finishStep($stepID)
    {
        $step = $this->deploy->getStepById($stepID);

        if($_POST)
        {
            $changes = $this->deploy->finishStep($stepID);
            if(dao::isError()) return $this->sendError(dao::getError());

            if($changes || $this->post->comment)
            {
                $actionID = $this->loadModel('action')->create('deploystep', $stepID, "finished", $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title = $this->lang->deploy->finishStep;
        $this->view->step  = $step;
        $this->view->users = $this->deploy->getMembers($step->deploy);
        $this->display();
    }

    /**
     * 指派上线步骤。
     * Update assign of step.
     *
     * @param  int    $stepID
     * @access public
     * @return void
     */
    public function assignTo($stepID)
    {
        $step = $this->deploy->getStepById($stepID);

        if($_POST)
        {
            $changes = $this->deploy->assignTo($stepID);
            if(dao::isError()) $this->sendError(dao::getError());

            if($changes or $this->post->comment)
            {
                $actionID = $this->loadModel('action')->create('deploystep', $stepID, "Assigned", $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->title = $this->lang->deploy->assignTo;
        $this->view->step  = $step;
        $this->view->users = $this->deploy->getMembers($step->deploy);
        $this->display();
    }

    /**
     * View the step.
     *
     * @param  int    $stepID
     * @access public
     * @return void
     */
    public function viewStep($stepID)
    {
        $stepID = (int)$stepID;
        $step   = $this->loadModel('deploy')->getStepById($stepID);
        if(empty($step)) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('deploy', 'browse'))));

        $this->view->title   = $step->title;
        $this->view->step    = $step;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        $this->view->actions = $this->loadModel('action')->getList('deploystep', $stepID);
        $this->display();
    }

    /**
     * Edit the step.
     *
     * @param  int    $stepID
     * @access public
     * @return void
     */
    public function editStep($stepID)
    {
        $step = $this->deploy->getStepById($stepID);

        if($_POST)
        {
            $form = form::data($this->config->deploy->form->editStep)->get();

            if($form->status == 'wait' && !empty($form->finishedBy)) return $this->sendError(array('finishedBy' => array($this->lang->deploy->errorStatusWait)));
            if($form->status == 'done' && empty($form->finishedBy)) return $this->sendError(array('finishedBy' => array($this->lang->deploy->errorStatusDone)));
            if($form->finishedBy != $step->finishedBy) $form->finishedDate = helper::now();
            if($form->assignedTo != $step->assignedTo) $form->assignedDate = helper::now();

            $changes = $this->deploy->updateStep($stepID, $form);
            if(dao::isError()) return $this->sendError(dao::getError());

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('deploystep', $stepID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('load' => true));
        }

        unset($this->lang->deploy->stageList['testing']);

        $this->view->step  = $step;
        $this->view->users = $this->deploy->getMembers($step->deploy);
        $this->display();
    }

    /**
     * Delete the step.
     *
     * @param  int    $stepID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function deleteStep($stepID)
    {
        $this->deploy->delete(TABLE_DEPLOYSTEP, $stepID);

        return dao::isError() ? $this->sendError(dao::getError()) : $this->sendSuccess(array('load' => true, 'message' => $this->lang->deleteSuccess));
    }

    /**
     * 上线一个发布申请。
     * Publish a deploy.
     *
     * @param  int $deployID
     * @access public
     * @return void
     */
    public function publish($deployID)
    {
        $changes = $this->deploy->changeStatus($deployID, 'publish', new stdclass());
        if(dao::isError()) return $this->sendError(dao::getError());

        $actionID = $this->loadModel('action')->create('deploy', $deployID, 'deployPublished');
        if($changes) $this->action->logHistory($actionID, $changes);

        return $this->sendSuccess(array('load' => true));
    }
}
