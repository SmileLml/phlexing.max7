<?php
class charter extends control
{
    /**
     * Browse.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadLang('project');

        $browseType = strtolower($browseType);
        $this->session->set('charterList', $this->app->getURI(true));
        $this->session->set('browseType', $browseType);
        setcookie('browseType', $browseType);

        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('charter', 'browse', "browseType=bySearch&param=myQueryID");
        $this->charter->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $charters = $this->charter->getList($browseType, $queryID, $orderBy, $pager);
        foreach($charters as $charter)
        {
            if($charter->budget >= 0) $charter->budget = zget($this->lang->project->currencySymbol, $charter->budgetUnit) . ' ' . $charter->budget;

            $charterFiles = !empty($charter->filesConfig) ? json_decode($charter->filesConfig) : json_decode($this->config->custom->charterFiles);
            $levelList    = array();
            foreach($charterFiles as $groups)
            {
                $levelList[$groups->key] = $groups->level;
            }
            $charter->levelList = $levelList;
        }

        $this->view->title      = $this->lang->charter->browse;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->charters   = $charters;
        $this->view->browseType = $browseType;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->param      = $param;
        $this->display();
    }

    /**
     * Create.
     *
     * @param  string $level
     * @access public
     * @return void
     */
    public function create($level = '')
    {
        if($_POST)
        {
            $charterID = $this->charter->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($charterID);
            if(!$message) $message = $this->lang->saveSuccess;

            return $this->send(array('result' => 'success', 'message' => $message, 'load' => inlink('browse')));
        }

        $charterFiles = json_decode($this->config->custom->charterFiles);
        $levelList    = array();
        $fileList     = array();
        $objectType   = 'plan';
        foreach($charterFiles as $groups)
        {
            $levelList[$groups->key] = $groups->level;
            if($level === '') $level = $groups->key;
            if($level == $groups->key)
            {
                $objectType = $groups->type;
                foreach($groups->projectApproval as $fileInfo) $fileList[$fileInfo->index] = $fileInfo->name;
            }
        }

        $this->view->title               = $this->lang->charter->create;
        $this->view->budgetUnitList      = $this->loadModel('project')->getBudgetUnitList();
        $this->view->users               = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->products            = $this->loadModel('product')->getPairs('noclosed');
        $this->view->levelList           = $levelList;
        $this->view->level               = $level;
        $this->view->fileList            = $fileList;
        $this->view->roadmaps            = array();
        $this->view->plans               = array();
        $this->view->objectType          = $objectType;
        $this->view->loadUrl             = $this->createLink('charter', 'create', "level={level}");
        $this->view->multiBranchProducts = $this->product->getMultiBranchPairs(0);
        $this->display();
    }

    /**
     * Edit.
     *
     * @param  int    $charterID
     * @param  string $level
     * @access public
     * @return void
     */
    public function edit($charterID = 0, $level = '')
    {
        $charter = $this->charter->getByID($charterID);

        if($_POST)
        {
            $changes = $this->charter->update($charterID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('charter', $charterID, 'edited');
                $this->action->logHistory($actionID, $changes);
                foreach($changes as $change)
                {
                    if($change['field'] == 'roadmap')
                    {
                       $this->loadModel('roadmap')->setStatus($change['old'], 'wait');
                       $this->loadModel('roadmap')->setStatus($change['new'], 'launching');
                       break;
                    }
                }
            }

            $message = $this->executeHooks($charterID);
            if(!$message) $message = $this->lang->saveSuccess;

            return $this->send(array('result' => 'success', 'message' => $message, 'load' => inlink('browse')));
        }

        if($level === '') $level = $charter->level;
        $charterFiles = !empty($charter->filesConfig) ? json_decode($charter->filesConfig) : json_decode($this->config->custom->charterFiles);
        $levelList    = array();
        $fileList     = array();
        $objectType   = $charter->type;
        foreach($charterFiles as $groups)
        {
            $levelList[$groups->key] = $groups->level;
            if($level == $groups->key)
            {
                $objectType = $groups->type;
                foreach($groups->projectApproval as $fileInfo) $fileList[$fileInfo->index] = $fileInfo->name;
            }
        }

        $this->view->title               = $this->lang->charter->edit;
        $this->view->budgetUnitList      = $this->loadModel('project')->getBudgetUnitList();
        $this->view->users               = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->products            = $this->loadModel('product')->getPairs('noclosed', 0, trim($charter->product, ','));
        $this->view->charter             = $charter;
        $this->view->levelList           = $levelList;
        $this->view->level               = $level;
        $this->view->fileList            = $fileList;
        $this->view->objectType          = $objectType;
        $this->view->loadUrl             = $this->createLink('charter', 'edit', "charterID={$charterID}&level={level}");
        $this->view->multiBranchProducts = $this->product->getMultiBranchPairs(0);
        $this->display();
    }

    /**
     * Delete a charter.
     *
     * @param  int    $charterID
     * @access public
     * @return void
     */
    public function delete($charterID)
    {
        $charter = $this->charter->getByID($charterID);
        $this->charter->delete(TABLE_CHARTER, $charterID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $roadmap = $this->loadModel('roadmap')->getByID($charter->roadmap);
        if($roadmap and $roadmap->status != 'wait')
        {
            $this->dao->update(TABLE_ROADMAP)->set('status')->eq('wait')->where('id')->eq($charter->roadmap)->exec();
            $this->loadModel('action')->create('roadmap', $charter->roadmap, 'changedbycharter', '', $charterID);
        }

        $message = $this->executeHooks($charterID);
        if(!$message) $message = $this->lang->saveSuccess;

        return $this->send(array('result' => 'success', 'message' => $message, 'load' => true));
    }

    /**
     * Close a charter.
     *
     * @param  int    $charterID
     *
     * @access public
     * @return void
     */
    public function close($charterID = 0)
    {
        $charter = $this->charter->getByID($charterID);

        if(!empty($_POST))
        {
            $this->charter->close($charterID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($charterID);
            if(!$message) $message = $this->lang->saveSuccess;

            return $this->send(array('result' => 'success', 'message' => $message, 'load' => true, 'closeModal' => true));
        }

        $this->view->title   = $this->lang->close;
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted');
        $this->view->actions = $this->loadModel('action')->getList('charter', $charterID);
        $this->view->charter = $charter;
        $this->display();
    }

    /**
     * review
     *
     * @param int     $charterID
     * @access public
     * @return void
     */
    public function activate($charterID = 0)
    {
        $charter = $this->charter->getByID($charterID);

        $changes = $this->charter->activate($charterID);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if($changes || $this->post->comment != '')
        {
            $actionID = $this->loadModel('action')->create('charter', $charterID, 'activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        $message = $this->executeHooks($charterID);
        if(!$message) $message = $this->lang->saveSuccess;

        return $this->send(array('result' => 'success', 'message' => $message, 'load' => true));
    }

    /**
     * 审批。
     * Review approval.
     *
     * @param  int    $charterID
     * @access public
     * @return void
     */
    public function review($charterID = 0)
    {
        $this->loadModel('approval');
        if(!empty($_POST))
        {
            $charter = form::data($this->config->charter->form->review)->add('id', $charterID)->get();
            $this->charter->review($charter, $charterID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($charterID);
            if(!$message) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => true));
        }

        $approval    = $this->approval->getByObject('charter', $charterID);
        $nodeGroups  = $this->approval->getNodeOptions(json_decode($approval->nodes));
        $doingNode   = $this->dao->select('node,COUNT(1) as count')->from(TABLE_APPROVALNODE)->where('approval')->eq($approval->id)->andWhere('status')->eq('doing')->andWhere('type')->eq('review')->groupBy('node')->fetch();
        $currentNode = zget($nodeGroups, $doingNode->node);

        $this->view->title       = $this->lang->charter->review;
        $this->view->charter     = $this->charter->getByID($charterID);
        $this->view->actions     = $this->loadModel('action')->getList('charter', $charterID);
        $this->view->currentNode = $currentNode;
        $this->display();
    }

    /**
     * 撤回审批申请。
     * Cancel approval.
     *
     * @param  int    $charterID
     * @access public
     * @return void
     */
    public function approvalCancel($charterID = 0)
    {
        $this->charter->approvalCancel($charterID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $message = $this->executeHooks($charterID);
        if(!$message) $message = $this->lang->saveSuccess;
        return $this->send(array('result' => 'success', 'message' => $message, 'load' => true));
    }

    /**
     * 查看审批进度。
     * View approval progress.
     *
     * @param  int    $approvalID
     * @access public
     * @return void
     */
    public function approvalProgress($approvalID = 0)
    {
       echo $this->fetch('approval', 'progress', "approvalID=$approvalID");
    }

    /**
     * 根据立项关联的路标以及立项状态查询相关立项。
     * Fetch charter by roadmap and status.
     *
     * @param  int    $roadmapID
     * @param  string $status
     * @access public
     * @return void
     */
    public function fetchPairsByRoadmap($roadmapID = 0, $status = '')
    {
        return  $this->dao->select('*')->from(TABLE_CHARTER)
            ->where('deleted')->eq(0)
            ->andWhere('roadmap')->eq($roadmapID)
            ->beginIF($status)->andWhere('status')->eq($status)
            ->fetch();
    }

    /**
     * view.
     *
     * @access public
     * @return void
     */
    public function view($charterID = 0)
    {
        $this->session->set('programList', $this->app->getURI(true), $this->app->tab);

        $charter = $this->charter->getByID($charterID);
        if(!$charter) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('charter', 'browse'))));

        $this->app->loadLang('project');
        if($charter->budget >= 0) $charter->budget = zget($this->lang->project->currencySymbol, $charter->budgetUnit) . ' ' . $charter->budget;

        $charterFiles = !empty($charter->filesConfig) ? json_decode($charter->filesConfig) : json_decode($this->config->custom->charterFiles);
        $levelList    = array();
        foreach($charterFiles as $groups) $levelList[$groups->key] = $groups->level;

        $this->view->title          = $this->lang->charter->view;
        $this->view->budgetUnitList = $this->loadModel('project')->getBudgetUnitList();
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->products       = arrayUnion(array(0 => ''), $this->loadModel('product')->getByIdList(explode(',', trim($charter->product, ','))));
        $this->view->charter        = $charter;
        $this->view->actions        = $this->loadModel('action')->getList('charter', $charterID);
        $this->view->modules        = $this->loadModel('tree')->getAllModulePairs('story');
        $this->view->groupDate      = $this->charter->getGroupDataByID($charterID);
        $this->view->stories        = $this->dao->select('*')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('roadmap')->in($charter->roadmap)->andWhere('roadmap')->ne('')->fetchAll();
        $this->view->levelList      = $levelList;
        $this->view->programList    = $this->charter->getProgramAndProject($charterID);
        $this->view->branchGroups   = $this->charter->getLinkedBranchGroups($charterID);

        $this->display();
    }

    /**
     * Build the upload form.
     *
     * @param  string $filesName
     * @param  string $buttonName
     * @param  string $labelsName
     * @access public
     * @return void
     */
    public function buildFileForm($filesName = "files", $buttonName = '', $labelsName = "labels")
    {
        $this->loadModel('file');
        if(!file_exists($this->file->savePath))
        {
            printf($this->lang->file->errorNotExists, $this->file->savePath);
            return false;
        }
        elseif(!is_writable($this->file->savePath))
        {
            printf($this->lang->file->errorCanNotWrite, $this->file->savePath, $this->file->savePath);
            return false;
        }

        $this->view->filesName  = $filesName;
        $this->view->labelsName = $labelsName;
        $this->view->buttonName = $buttonName ? $buttonName : $this->lang->file->addFile;
        $this->display();
    }

    /**
     * Load roadmap stories.
     *
     * @param  int    $productID
     * @param  string $roadmapIDList
     * @param  int    $roadmapID
     * @access public
     * @return void
     */
    public function loadRoadmapStories($productID = 0, $roadmapIDList = '', $roadmapID = 0)
    {
        $this->loadModel('story');
        $roadmaps = $productID ? $this->loadModel('roadmap')->getPairs($productID, 'all', 'nolaunching') : array();
        if($roadmapIDList && $productID)
        {
            $roadmapIDs = explode(',', $roadmapIDList);
            $roadmapIDs = array_flip($roadmapIDs);
            $roadmaps   = array_intersect_key($roadmaps, $roadmapIDs);
        }
        $roadmapID = $roadmapID ? $roadmapID : key($roadmaps);
        $stories   = $this->loadModel('roadmap')->getRoadmapStories($roadmapID);
        $this->view->stories       = $stories;
        $this->view->roadmaps      = $roadmaps;
        $this->view->roadmapID     = $roadmapID;
        $this->view->productID     = $productID;
        $this->view->roadmapIDList = $roadmapIDList;
        $this->view->modules       = !empty($roadmapID) ? $this->loadModel('tree')->getAllModulePairs('story') : array();
        $this->view->storyGrades   = $this->loadModel('demand')->getStoryGradeList();
        $this->display();
    }

    /**
     * 发起立项审批。
     * Initiate project approval.
     *
     * @param  int    $charterID
     * @access public
     * @return void
     */
    public function projectApproval($charterID = 0)
    {
        $this->app->loadLang('approval');
        $charter = $this->charter->getByID($charterID);

        if(trim($charter->plan, ','))
        {
            $charterPlans = explode(',', trim($charter->plan, ','));
            $this->charter->checkProductplan(array($charterPlans), $charterID);
        }

        if(trim($charter->roadmap, ','))
        {
            $charterRoadmaps = explode(',', trim($charter->roadmap, ','));
            $this->charter->checkProductRoadmap(array($charterRoadmaps), $charterID);
        }

        if(dao::isError())
        {
            $link = $this->inlink('edit', "charterID=$charterID");
            $tip  = sprintf($this->lang->charter->tips->needAdjust, trim($charter->roadmap, ',') ? $this->lang->roadmap->common : $this->lang->productplan->plan);
            return $this->send(array('result' => 'success', 'callback' => "zui.Modal.confirm({message: '{$tip}', 'actions': [{key: 'confirm', text: '{$this->lang->charter->toAdjust}', btnType: 'primary', className: 'btn-wide'}, {key: 'cancel', text: '{$this->lang->cancel}'}]}).then((res) => {if(res){openPage('$link');} else {zui.Modal.hide();}});"));
        }

        if($_POST)
        {
            $this->charter->projectApproval($charterID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($charterID);
            if(!$message) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => true, 'closeModal' => true));
        }

        $this->view->charter             = $charter;
        $this->view->users               = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->approvalReviewDatas = $this->loadModel('flow')->getApprovalReviewerDatas('charter', $charter);

        $this->display();
    }

    /**
     * 发起结项审批。
     * Initiate project completion approval.
     *
     * @param  int    $charterID
     * @param  string $from        list|view
     * @access public
     * @return void
     */
    public function completionApproval($charterID = 0, $from = 'list')
    {
        $unclosedObjects = $this->charter->getProgramAndProject($charterID, false);
        if(!empty($unclosedObjects)) return $this->send(array('result' => 'fail', 'message' => $this->lang->charter->tips->unclosedObjects));

        $this->app->loadLang('approval');
        $charter = $this->charter->getByID($charterID);

        if($_POST)
        {
            $this->charter->completionApproval($charterID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $url     = $from == 'list' ? inlink('browse') : inlink('view', "charterID=$charterID");
            $message = $this->executeHooks($charterID);
            if(!$message) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $url));
        }

        $charterFiles = !empty($charter->filesConfig) ? json_decode($charter->filesConfig) : json_decode($this->config->custom->charterFiles);
        $fileList     = array();
        foreach($charterFiles as $groups)
        {
            if($charter->level == $groups->key)
            {
                foreach($groups->completeApproval as $fileInfo) $fileList[$fileInfo->index] = $fileInfo->name;
            }
        }

        $this->view->title               = $this->lang->charter->completionApproval;
        $this->view->charter             = $charter;
        $this->view->users               = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->approvalReviewDatas = $this->loadModel('flow')->getApprovalReviewerDatas('charter', $charter);
        $this->view->fileList            = $fileList;
        $this->display();
    }

    /**
     * 取消立项审批。
     * Cancel Project Approval.
     *
     * @param  int    $charterID
     * @param  string $from        list|view
     * @access public
     * @return void
     */
    public function cancelProjectApproval($charterID = 0, $from = 'list')
    {
        $this->app->loadLang('approval');
        $charter = $this->charter->getByID($charterID);
        $isWait  = $this->charter->isClickable($charter, 'projectapproval');

        if(!$isWait)
        {
            $unclosedObjects = $this->charter->getProgramAndProject($charterID, false);
            if(!empty($unclosedObjects)) return $this->send(array('result' => 'fail', 'message' => $this->lang->charter->tips->unclosedObjects));
        }

        if($_POST)
        {
            if($isWait)
            {
                $this->charter->cancel($charterID);
            }
            else
            {
                $this->charter->cancelProjectApproval($charterID);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $url     = $from == 'list' ? inlink('browse') : inlink('view', "charterID=$charterID");
            $message = $this->executeHooks($charterID);
            if(!$message) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $url, 'closeModal' => true));
        }

        $charterFiles = !empty($charter->filesConfig) ? json_decode($charter->filesConfig) : json_decode($this->config->custom->charterFiles);
        $fileList     = array();
        foreach($charterFiles as $groups)
        {
            if($charter->level == $groups->key)
            {
                foreach($groups->cancelApproval as $fileInfo) $fileList[$fileInfo->index] = $fileInfo->name;
            }
        }

        $showPage = $isWait ? 'cancel' : 'cancelProjectApproval';

        $this->view->title               = $this->lang->charter->cancelProjectApproval;
        $this->view->charter             = $charter;
        $this->view->users               = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->approvalReviewDatas = $this->loadModel('flow')->getApprovalReviewerDatas('charter', $charter);
        $this->view->fileList            = $fileList;
        $this->display('charter', $showPage);
    }

    /**
     * 激活立项审批。
     * Activate Project Approval.
     *
     * @param  int    $charterID
     * @access public
     * @return void
     */
    public function activateProjectApproval($charterID = 0)
    {
        $this->app->loadLang('approval');
        $charter = $this->charter->getByID($charterID);

        if(trim($charter->plan, ','))
        {
            $charterPlans = explode(',', trim($charter->plan, ','));
            $this->charter->checkProductplan(array($charterPlans), $charterID);
        }

        if(trim($charter->roadmap, ','))
        {
            $charterRoadmaps = explode(',', trim($charter->roadmap, ','));
            $this->charter->checkProductRoadmap(array($charterRoadmaps), $charterID);
        }

        if(dao::isError())
        {
            $link = $this->inlink('edit', "charterID=$charterID");
            $tip  = sprintf($this->lang->charter->tips->needAdjust, trim($charter->roadmap, ',') ? $this->lang->roadmap->common : $this->lang->productplan->plan);
            return $this->send(array('result' => 'success', 'callback' => "zui.Modal.confirm({message: '{$tip}', 'actions': [{key: 'confirm', text: '{$this->lang->charter->toAdjust}', btnType: 'primary', className: 'btn-wide'}, {key: 'cancel', text: '{$this->lang->cancel}'}]}).then((res) => {if(res){openPage('$link');} else {zui.Modal.hide();}});"));
        }

        if($_POST)
        {
            if($charter->prevCanceledStatus == 'wait')
            {
                $this->charter->activate($charterID);
            }
            else
            {
                $this->charter->activateProjectApproval($charterID);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($charterID);
            if(!$message) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => true, 'closeModal' => true));
        }

        $this->view->title               = $this->lang->charter->activateProjectApproval;
        $this->view->charter             = $charter;
        $this->view->users               = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->approvalReviewDatas = $this->loadModel('flow')->getApprovalReviewerDatas('charter', $charter);

        $showPage = $charter->prevCanceledStatus == 'wait' ? 'activate' : 'activateProjectApproval';
        $this->display('charter', $showPage);
    }

    /**
     * Ajax get roadmaps by product.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetRoadmaps($productID = 0)
    {
        $options = $this->loadModel('roadmap')->getPairs($productID, '', 'nolaunched');

        return print(html::select('roadmap', $options, '', "class='from-control chosen' onchange='changeLink(this.value)'"));
    }

    /**
     * Ajax get charter info by id.
     *
     * @param  int    $charterID
     * @access public
     * @return void
     */
    public function ajaxGetCharterInfo($charterID = 0)
    {
        $charter = $this->charter->getByID($charterID);
        $charter->productName = $this->dao->select('name')->from(TABLE_PRODUCT)->where('id')->eq($charter->product)->fetch('name');
        $charter->roadmapName = $this->dao->select('name')->from(TABLE_ROADMAP)->where('id')->eq($charter->roadmap)->fetch('name');

        return print(json_encode($charter));
    }

    /**
     * AJAX: Get product plans by product.
     *
     * @param  int    $productID
     * @param  string $branchID
     * @access public
     * @return string
     */
    public function ajaxGetPlans($productID = 0, $branchID = '')
    {
        if(!$productID) return $this->send(array());

        $plans = $this->loadModel('productplan')->getPlansForCharter(array($productID), '', $branchID);
        if(empty($plans[$productID])) $this->send(array());

        $plans = array_filter($plans[$productID]);

        $planList = array();
        foreach($plans as $planID => $planTitle) $planList[] = array('text' => $planTitle, 'value' => $planID);

        return $this->send($planList);
    }
}
