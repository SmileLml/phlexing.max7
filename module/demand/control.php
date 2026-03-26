<?php
class demand extends control
{
    /**
     * Browse demand list.
     *
     * @param  int    $poolID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($poolID = 0, $browseType = '', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $poolID = $this->loadModel('demandpool')->setMenu($poolID);

        if(empty($browseType))
        {
            $browseType = 'assignedtome';
            $demands = $this->demand->getList($poolID, $browseType, 0, $orderBy, null, '', true);
            if(empty($demands)) $browseType = 'all';
        }

        $this->loadModel('datatable');
        $datatableId  = $this->moduleName . ucfirst($this->methodName);
        if(!isset($this->config->datatable->$datatableId->mode))
        {
            $this->loadModel('setting')->setItem("{$this->app->user->account}.datatable.$datatableId.mode", 'datatable');
            $this->config->datatable->$datatableId = new stdclass();
            $this->config->datatable->$datatableId->mode = 'datatable';
        }

        $browseType = strtolower($browseType);

        $this->session->set('demandList', $this->app->getURI(true), 'demandpool');

        setcookie('demandModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);

        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('demand', 'browse', "poolID=$poolID&browseType=bySearch&param=myQueryID");
        $this->demand->buildSearchForm($poolID, $queryID, $actionURL);

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->loadModel('custom');
        $demands = $this->demand->getList($poolID, $browseType, $queryID, $orderBy, $pager, '', true);
        if(!empty($demands)) $demands = $this->demand->mergeReviewer($demands);
        foreach($demands as $demand) $demand->relatedObject = $this->custom->getRelatedObjectList($demand->id, 'demand', 'byRelation', true);

        $relatedObjectList = $this->loadModel('custom')->getRelatedObjectList(array_keys($demands), 'demand', 'byRelation', true);
        foreach($demands as $demand) $demand->relatedObject = zget($relatedObjectList, $demand->id, 0);

        $this->view->title       = $this->lang->demand->browse;
        $this->view->demands     = $demands;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->browseType  = $browseType;
        $this->view->poolID      = $poolID;
        $this->view->demandpools = $this->demandpool->getPairs();
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->products    = $this->loadModel('product')->getPairs('noclosed');
        $this->display();
    }

    /**
     * 创建需求池需求。
     * Create a demand.
     *
     * @param  int    $poolID
     * @param  int    $demandID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function create($poolID = 0, $demandID = 0, $extra = '')
    {
        $extra  = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);
        $fromType = isset($output['fromType']) ? $output['fromType'] : '';
        $fromID   = isset($output['fromID'])   ? $output['fromID']   : 0;

        /* Set Menu. */
        if($fromType == 'feedback')
        {
            $this->loadModel('feedback')->setMenu();
        }
        else
        {
            $poolID = $this->loadModel('demandpool')->setMenu($poolID);
        }

        if($_POST)
        {
            $formData = form::data($this->config->demand->form->create)->get();
            $demand   = $this->demandZen->prepareCreateExtras($formData);
            if(!$demand) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $demandID = $this->demand->create($demand);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->demandZen->responseAfterCreate($poolID, $demandID, $fromType, $fromID);
        }

        /* 处理复制需求池需求，反馈转需求池需求。 */
        /* Handle copy demand and feedback to demand.*/
        $demand = $this->demandZen->extractObjectFromExtras($demandID, $output);

        $this->demandZen->buildCreateForm($poolID);

        $this->view->from      = $fromType;
        $this->view->demand    = $demand;
        $this->view->loadUrl   = $this->createLink($this->app->rawModule, $this->app->rawMethod, "poolID={pool}&demandID={$demandID}&extra=fromType={$fromType},fromID={$fromID}");
        $this->view->productID = $this->session->feedbackProduct ? $this->session->feedbackProduct : 0;
        $this->view->fromID    = $fromID;
        $this->display();
    }

    /**
     * Batch create demands.
     *
     * @param  int    $poolID
     * @param  int    $demandID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function batchCreate($poolID = 0, $demandID = 0, $confirm = 'no')
    {
        $poolID = $this->loadModel('demandpool')->setMenu($poolID);

        if($demandID)
        {
            $demand = $this->demand->getByID($demandID);
            if(in_array($demand->stage, array('distributed', 'inroadmap')) && $confirm == 'no')
            {
                $confirmURL = $this->createLink('demand', 'batchCreate', "poolID=$poolID&demand=$demandID&confirm=yes");
                $cancelURL  = $this->session->demandList;
                return $this->send(array('result' => 'fail', 'load' => array('confirm' => $this->lang->demand->subdivideNotice, 'confirmed' => $confirmURL, 'canceled' => $cancelURL)));
            }
        }

         $this->extendRequireFields();
        if($_POST)
        {
            $demandIdList = $this->demand->batchCreate($poolID);
            if(dao::isError())
            {
                $response = array('result' => 'fail', 'message' => dao::getError());
                return $this->send($response);
            }

            if($demandID && !empty($demandIdList)) $this->demand->subdivide($demandID, $demandIdList);

            foreach($demandIdList as $demandID) $message = $this->executeHooks($demandID);
            if(!$message) $message = $this->lang->saveSuccess;

            $response = array('result' => 'success', 'message' => $message, 'locate' => $this->session->demandList ? $this->session->demandList : inlink('browse', "poolID=$poolID&browseType=all"));
            return $this->send($response);
        }

        /* Set Custom*/
        $customFields = array();
        foreach(explode(',', $this->config->demand->list->customBatchCreateFields) as $field) $customFields[$field] = $this->lang->demand->$field;

        $products = $this->loadModel('product')->getPairs('noclosed');
        $pool     = $this->demandpool->getByID($poolID);
        if(!empty($pool->products)) $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($pool->products)->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()->andWhere('deleted')->eq(0)->andWhere('status')->ne('closed')->orderBy('id')->fetchPairs();

        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->demand->custom->batchCreateFields;

        $this->view->title        = $this->lang->demand->batchCreate;
        $this->view->users        = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->view->assignToList = $this->demandpool->getAssignedTo($poolID);
        $this->view->pool         = $pool;
        $this->view->poolID       = $poolID;
        $this->view->demand       = $demandID ? $this->demand->getByID($demandID) : null;
        $this->view->demandID     = $demandID;
        $this->view->products     = $products;
        $this->view->demands      = $this->demandZen->getDataFromUploadImages();
        $this->display();
    }

    /**
     * 编辑需求池需求。
     * Edit a demand.
     *
     * @param  int $demandID
     * @access public
     * @return void
     */
    public function edit($demandID = 0)
    {
        $oldDemand = $this->loadModel('demand')->getByID($demandID);
        $this->loadModel('demandpool')->setMenu($oldDemand->pool);

        if($_POST)
        {
            $demand = $this->demandZen->buildDemandForEdit($oldDemand);
            $this->demand->update($demand);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->demandZen->responseAfterEdit($oldDemand, $demand);
        }

        $products = $this->loadModel('product')->getPairs('noclosed');
        $pool     = $this->demandpool->getByID($oldDemand->pool);
        if(!empty($pool->products)) $products = $this->product->getProductByPool($oldDemand->pool, 'wait,normal', $oldDemand->product, true);

        $this->view->title               = $this->lang->demand->edit;
        $this->view->users               = $this->loadModel('user')->getPairs('noclosed');
        $this->view->demand              = $oldDemand;
        $this->view->actions             = $this->loadModel('action')->getList('demand', $demandID);
        $this->view->products            = $products;
        $this->view->parents             = $this->demand->getParentDemandPairs($oldDemand->pool, $demandID);
        $this->view->reviewers           = $this->demandpool->getReviewers($oldDemand->pool, $oldDemand->createdBy);
        $this->view->assignToList        = $this->demandpool->getAssignedTo($oldDemand->pool);
        $this->view->needReview          = (($this->config->demand->needReview == 0 or !$this->demand->checkForceReview()) and empty($oldDemand->reviewer)) ? "checked='checked'" : "";
        $this->view->demandpools         = $this->demandpool->getPairs();
        $this->view->distributedProducts = $this->demand->getDistributedProducts($demandID);
        $this->view->poolID              = $pool->id;
        $this->display();
    }

    /**
     * View a demand.
     *
     * @param  int    $demandID
     * @access public
     * @return void
     */
    public function view($demandID = 0, $version = 0)
    {
        $demand = $this->demand->getByID($demandID, $version);
        if(!$demand) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('demandpool', 'browse'))));

        $uri = $this->app->getURI(true);
        $this->session->set('demandList', $uri);
        $this->session->set('storyList', $uri, 'demandpool');

        foreach($demand->files as $file) $file->extra = '';

        $demand = $this->demand->mergeReviewer($demand, true);
        if($this->config->vision == 'or') $this->loadModel('demandpool')->setMenu($demand->pool);

        $version = empty($version) ? $demand->version : $version;

        $this->view->title          = $this->lang->demand->view;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions        = $this->loadModel('action')->getList('demand', $demandID);
        $this->view->demand         = $demand;
        $this->view->version        = $version;
        $this->view->demandpools    = $this->config->vision == 'or' ? $this->demandpool->getPairs() : array();
        $this->view->demands        = $this->demand->getPairs($demand->pool);
        $this->view->products       = array(0 => '') + $this->loadModel('product')->getPairs('all');
        $this->view->preAndNext     = $this->loadModel('common')->getPreAndNextObject('demand', $demandID);
        $this->view->poolID         = $demand->pool;
        $this->view->storyGrades    = $this->demand->getStoryGradeList();
        $this->view->roadmapPlans   = $this->loadModel('roadmap')->getRoadmapAndPlanByProducts();
        $this->view->relatedObjects = $this->loadModel('custom')->getRelatedObjectList($demand->id, 'demand', 'byObject');

        $this->display();
    }

    /**
     * 需求池需求的指派给页面。
     * Assign the demand to a user.
     *
     * @param  int    $demandID
     * @access public
     * @return void
     */
    public function assignTo($demandID)
    {
        $oldDemand = $this->demand->getByID($demandID);

        if(!empty($_POST))
        {
            /* 初始化 demand 数据。*/
            /* Init demand data. */
            $demand = form::data($this->config->demand->form->assignTo, $demandID)->get();

            $this->demand->assign($demand, $oldDemand);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($demandID);
            if(!$message) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        $this->view->demand  = $oldDemand;
        $this->view->actions = $this->loadModel('action')->getList('demand', $demandID);
        $this->view->users   = $this->loadModel('demandpool')->getAssignedTo($oldDemand->pool);
        $this->display();
    }

    /**
     * Review a demand.
     *
     * @param  int    $demandID
     * @param  string $from      product|project
     * @param  string $demandType demand|requirement
     * @access public
     * @return void
     */
    public function review($demandID)
    {
        $this->loadModel('story');

        $this->extendRequireFields($demandID);
        if(!empty($_POST))
        {
            $this->demand->review($demandID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($demandID);
            if(!$message) $message = $this->lang->saveSuccess;
            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $message, 'load' => true, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $message, 'locate' => helper::createLink('demand', 'view', "demandID=$demandID")));
        }

        /* Get demand and product. */
        $demand     = $this->demand->getById($demandID);
        $demandpool = $this->loadModel('demandpool')->getByID($demand->pool);
        $this->demandpool->setMenu($demandpool->id);

        /* Set the review result options. */
        $reviewers = $this->demand->getReviewerPairs($demandID, $demand->version);
        $this->lang->demand->resultList = $this->lang->demand->reviewResultList;

        if($demand->status == 'reviewing')
        {
            if($demand->version == 1) unset($this->lang->demand->resultList['revert']);
            if($demand->version > 1)  unset($this->lang->demand->resultList['reject']);
        }

        $this->view->title        = $this->lang->demand->review . $this->lang->hyphen . $demand->title;
        $this->view->demand       = $demand;
        $this->view->actions      = $this->loadModel('action')->getList('demand', $demandID);
        $this->view->users        = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->view->assignToList = $this->demandpool->getAssignedTo($demand->pool);
        $this->view->reviewers    = $reviewers;
        $this->view->isLastOne    = count(array_diff(array_keys($reviewers), explode(',', $demand->reviewedBy))) == 1 ? true : false;
        $this->view->poolID       = $demandpool->id;

        $this->display();
    }

    /**
     * Submit review.
     *
     * @param  int    $demandID
     * @param  string $demandType demand|requirement
     * @access public
     * @return void
     */
    public function submitReview($demandID, $demandType = 'demand')
    {
        $this->loadModel('demandpool');

        $this->extendRequireFields($demandID);
        if($_POST)
        {
            $changes = $this->demand->submitReview($demandID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('demand', $demandID, 'submitReview');
                $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($demandID);
            if(!$message) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }

        $demand     = $this->demand->getById($demandID);
        $demandpool = $this->demandpool->getById($demand->pool);

        /* Get demand reviewer. */
        if(!$demand->reviewer and $this->demand->checkForceReview())
        {
            $demand->reviewer = current(explode(',', trim($demandpool->reviewer, ',')));
            if(!$demand->reviewer) $demand->reviewer = current(explode(',', trim($demandpool->owner, ',')));
        }

        $reviewers = $this->demandpool->getReviewers($demand->pool, $demand->createdBy);

        $this->view->demand       = $demand;
        $this->view->actions      = $this->loadModel('action')->getList('demand', $demandID);
        $this->view->reviewers    = $reviewers;
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->needReview   = (($this->config->demand->needReview == 0 or !$this->demand->checkForceReview()) and empty($demand->reviewer)) ? "checked='checked'" : "";
        $this->view->lastReviewer = $this->demand->getLastReviewer($demand->id);

        $this->display();
    }

    /**
     * Recall the demand review or demand change.
     *
     * @param  int    $demandID
     * @param  string $confirm   no|yes
     * @access public
     * @return void
     */
    public function recall($demandID, $confirm = 'no')
    {
        $this->app->loadLang('demand');
        $this->app->loadLang('story');
        $demand = $this->demand->getById($demandID);

        if($confirm == 'no')
        {
            $confirmTips = $demand->status == 'changing' ? $this->lang->story->confirmRecallChange : $this->lang->story->confirmRecallReview;
            $confirmURL  = $this->createLink('demand', 'recall', "demandID=$demandID&confirm=yes");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message:'{$confirmTips}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL'});});"));
        }

        if($demand->status == 'changing')  $this->demand->recallChange($demandID);
        if($demand->status == 'reviewing') $this->demand->recallReview($demandID);

        $action = $demand->status == 'changing' ? 'recalledChange' : 'Recalled';
        $this->loadModel('action')->create('demand', $demandID, $action);

        $message = $this->executeHooks($demandID);
        if(!$message) $message = $this->lang->saveSuccess;
        return $this->send(array('result' => 'success', 'message' => $message, 'load' => true, 'closeModal' => true));
    }

    /**
     * Change a demand.
     *
     * @param  int    $demandID
     * @access public
     * @return void
     */
    public function change($demandID)
    {
        $this->loadModel('file');
        $this->loadModel('story');

        $this->extendRequireFields($demandID);
        if(!empty($_POST))
        {
            $changes = $this->demand->change($demandID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $action   = !empty($changes) ? 'Changed' : 'Commented';
                $actionID = $this->loadModel('action')->create('demand', $demandID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);

                /* Record submit review action. */
                $demand = $this->dao->findById((int)$demandID)->from(TABLE_DEMAND)->fetch();
                if($demand->status == 'reviewing') $this->action->create('demand', $demandID, 'submitReview');
            }

            $link    = $this->createLink('demand', 'view', "demandID=$demandID");
            $message = $this->executeHooks($demandID);
            if(!$message) $message = $this->lang->saveSuccess;
            return print($this->send(array('locate' => $link, 'message' => $message, 'result' => 'success')));
        }

        $demand = $this->demand->getByID($demandID);
        foreach($demand->files as $file)
        {
            $file->name  = $file->title;
            $file->extra = '';
            $file->url   = $this->createLink('file', 'download', "fileID=$file->id");
        }

        $this->loadModel('demandpool')->setMenu($demand->pool);

        $reviewer = $this->demand->getReviewerPairs($demandID, $demand->version);

        /* Assign. */
        $this->view->title        = $this->lang->demand->change . $this->lang->hyphen . $demand->title;
        $this->view->demand       = $demand;
        $this->view->needReview   = ($this->config->demand->needReview == 0 || !$this->demand->checkForceReview()) ? "checked='checked'" : "";
        $this->view->reviewer     = implode(',', array_keys($reviewer));
        $this->view->reviewers    = $this->demandpool->getReviewers($demand->pool, $this->app->user->account);
        $this->view->lastReviewer = $this->demand->getLastReviewer($demand->id);
        $this->view->users        = $this->loadModel('user')->getPairs('nodeleted');
        $this->view->actions      = $this->loadModel('action')->getList('demand', $demandID);
        $this->view->poolID       = $demand->pool;
        $this->display();
    }

    /**
     * Delete a demand.
     *
     * @param  int    $demandID
     * @access public
     * @return void
     */
    public function delete($demandID)
    {
        $demand = $this->demand->getByID($demandID);
        $this->demand->delete(TABLE_DEMAND, $demandID);

        if($demand->parent > 0)
        {
            $this->demand->updateParentField($demandID, $demand->parent);
            $this->loadModel('action')->create('demand', $demand->parent, 'deleteChildrenDemand', '', $demandID);

            $this->demand->updateParentDemandStage($demand->parent);
        }

        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));

        $locateLink = $this->createLink('demand', 'browse', "poolID=$demand->pool");
        $message    = $this->executeHooks($demandID);
        if(!$message) $message = $this->lang->saveSuccess;
        return $this->send(array('status' => 'success', 'message' => $message, 'closeModel' => true, 'load' => $locateLink));
    }

    /**
     * 关闭需求池需求。
     * Close a demand.
     *
     * @param  int    $demandID
     * @access public
     * @return void
     */
    public function close($demandID = 0)
    {
        $oldDemand = $this->demand->getByID($demandID);

        if(!empty($_POST))
        {
            /* 初始化 demand 数据。*/
            /* Init demand data. */
            $demand = form::data($this->config->demand->form->close, $demandID)
                ->add('lastEditedBy', $this->app->user->account)
                ->add('closedBy', $this->app->user->account)
                ->add('stage', 'closed')
                ->get();

            $this->demand->close($demand, $oldDemand);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($demandID);
            if(!$message) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        $demands = $this->demand->getPairs($oldDemand->pool);
        if($demands)
        {
            if(isset($demands[$demandID])) unset($demands[$demandID]);
            foreach($demands as $id => $title) $demands[$id] = "$id:$title";
        }

        $this->view->title   = $this->lang->demand->close;
        $this->view->demand  = $oldDemand;
        $this->view->demands = $demands;
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted');
        $this->view->actions = $this->loadModel('action')->getList('demand', $demandID);
        $this->display();
    }

    /**
     * 激活需求池需求。
     * Activate a demand.
     *
     * @param  int    $demandID
     * @access public
     * @return void
     */
    public function activate($demandID = 0)
    {
        if(!empty($_POST))
        {
            $postData = form::data($this->config->demand->form->activate, $demandID)
                ->add('id', $demandID)
                ->add('lastEditedBy', $this->app->user->account)
                ->get();

            $this->demand->activate($postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->executeHooks($demandID);
            if(!$message) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        $demand = $this->demand->getByID($demandID);

        $this->view->title   = $this->lang->demand->activate;
        $this->view->demand  = $demand;
        $this->view->users   = $this->loadModel('demandpool')->getAssignedTo($demand->pool);
        $this->view->actions = $this->loadModel('action')->getList('demand', $demandID);
        $this->display();
    }

    /**
     * Distribute.
     *
     * @param  int    $demandID
     * @access public
     * @return void
     */
    public function distribute($demandID = 0)
    {
        $this->extendRequireFields($demandID);
        if($_POST)
        {
            $redistributedTip = $this->demandZen->checkRedistribution($demandID, $_POST['product']);
            if($redistributedTip) return $this->send(array('result' => 'fail', 'message' => $redistributedTip));

            $changes = $this->demand->distribute($demandID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            if($changes || $this->post->comment != '')
            {
                $actionID = $this->loadModel('action')->create('demand', $demandID, 'distributed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($demandID);
            if(!$message) $message = $this->lang->saveSuccess;
            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
        }

        $demand         = $this->demand->getByID($demandID);
        $products       = $this->loadModel('product')->getPairs('noclosed');
        $pool           = $this->loadModel('demandpool')->getByID($demand->pool);
        $demandProducts = $demand->product ? explode(',', $demand->product) : array();

        if(!empty($pool->products)) $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($pool->products)->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()->andWhere('status')->ne('closed')->andWhere('deleted')->eq(0)->orderBy('id')->fetchPairs();
        $distributedProducts = $this->demand->getDistributedProducts($demandID);
        foreach($products as $id => $name)
        {
            if(isset($distributedProducts[$id])) unset($products[$id]);
        }

        $demandProducts = array_intersect(array_keys($products), $demandProducts);

        $productList = array();
        foreach($products as $productID => $productName) $productList[] = array('text' => $productName, 'value' => $productID, 'disabled' => in_array($productID, $demandProducts));

        $this->view->title             = $this->lang->demand->activate;
        $this->view->roadmaps          = array();
        $this->view->demand            = $demand;
        $this->view->users             = $this->loadModel('demandpool')->getAssignedTo($demand->pool);
        $this->view->actions           = $this->loadModel('action')->getList('demand', $demandID);
        $this->view->products          = $productList;
        $this->view->preProducts       = arrayUnion($demandProducts, array(''));
        $this->view->branchGroups      = $this->loadModel('branch')->getByProducts(array_keys($products));
        $this->view->roadmapPlanGroups = $this->loadModel('roadmap')->getRoadmapAndPlanByProducts(array_keys($products));
        $this->view->storyGrades       = $this->demand->getStoryGradeByProduct($demandProducts);

        $this->display();
    }

    /**
     * Retract distributed story.
     *
     * @param  int    $storyID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function retract($storyID, $confirm = 'no')
    {
        $story = $this->loadModel('story')->getById($storyID);
        if(in_array($story->status, array('closed', 'developing')) && $confirm == 'no')
        {
            $retractURL = $this->createLink('demand', 'retract', "storyID=$storyID&confirm=yes");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message:'{$this->lang->demand->retractedTips[$story->status]}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) openUrl('$retractURL', {load: 'modal', size: 'lg'});});"));
        }

        $this->loadModel($story->type);
        if($_POST)
        {
            $this->demand->retract($story);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        if($this->config->vision == 'or')
        {
            $this->lang->story->title           = str_replace($this->lang->SRCommon, $this->lang->storyCommon, $this->lang->story->title);
            $this->lang->story->affectedStories = str_replace($this->lang->story->story, $this->lang->storyCommon, $this->lang->story->affectedStories);
        }

        $this->story->getAffectedScope($story);
        $this->app->loadLang('task');
        $this->app->loadLang('testcase');
        $this->app->loadLang('product');

        $this->view->title   = $this->lang->demand->retract;
        $this->view->story   = $story;
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->view->actions = $this->loadModel('action')->getList('story', $storyID);
        $this->display();
    }

    /**
     * AjaxGetOptions.
     *
     * @param  int    $poolID
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxGetOptions($poolID = 0, $type = '')
    {
        if($type == 'assignedTo')
        {
            $options = $this->loadModel('demandpool')->getAssignedTo($poolID);
            return print(html::select('assignedTo', $options, '', "class='from-control picker-select'"));
        }

        if($type == 'reviewer')
        {
            $options = $this->loadModel('demandpool')->getReviewers($poolID, $this->app->user->account);
            return print(html::select('reviewer[]', $options, '', "class='from-control picker-select' multiple"));
        }
    }

    /**
     * AJAX: Get roadmap and plan by product.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $getObjectType
     * @access public
     * @return string
     */
    public function ajaxGetRoadmapPlans($productID = 0, $branch = 0, $getObjectType = 'all')
    {
        $roadmapPlanList = array(array('text' => '', 'value' => ''));
        if(empty($productID)) return $this->send($roadmapPlanList);

        $this->loadModel('roadmap');
        $this->loadModel('productplan');
        $branch            = empty($branch) ? 0 : $branch;
        $roadmapPlanGroups = $this->roadmap->getRoadmapAndPlanByProducts(array($productID), $getObjectType);

        if(!empty($roadmapPlanGroups[$productID][$branch]))
        {
            foreach($roadmapPlanGroups[$productID][$branch] as $id => $name)
            {
                $type      = strpos($id, '-') !== false ? substr($id, 0, strpos($id, '-')) : 'roadmap';
                $labelName = $type == 'roadmap' ? $this->lang->roadmap->common : $this->lang->productplan->shortCommon;

                $roadmapPlanList[] = array('text' => $name, 'value' => $id, 'leading' => array('html' => "<span class='label gray-pale rounded-xl clip'>{$labelName}</span"));
            }
        }

        return $this->send($roadmapPlanList);
    }

    /**
     * Export template.
     *
     * @access public
     * @return void
     */
    public function exportTemplate($poolID = 0)
    {
        $this->session->set('demandTransferParams', array('poolID' => $poolID));
        echo $this->fetch('transfer', 'exportTemplate', 'model=demand');
    }

    /**
     * Import excel file.
     *
     * @access public
     * @return void
     */
    public function import($poolID)
    {
        $url = inlink('showImport', "poolID=$poolID");
        $this->session->set('showImportURL', $url);
        echo $this->fetch('transfer', 'import', "model=demand");
    }

    /**
     * Import excel template file.
     *
     * @param  int    $poolID
     * @param  int    $pagerID
     * @param  int    $maxImport
     * @param  string $insert
     * @access public
     * @return void
     */
    public function showImport($poolID, $pagerID = 1, $maxImport = 0, $insert = '')
    {
        $this->session->set('demandTransferParams', array('poolID' => $poolID));
        if($_POST)
        {
            $this->demand->createFromImport($poolID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = inlink('showImport', "poolID=$poolID&pagerID=" . ($this->post->pagerID + 1) . "&maxImport=$maxImport&insert=" . zget($_POST, 'insert', ''));
            if($this->post->isEndPage) $locate = $this->createLink('demand', 'browse', "poolID=$poolID");

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $locate));
        }

        $poolID  = $this->loadModel('demandpool')->setMenu($poolID);
        $demands = $this->loadModel('transfer')->readExcel('demand', $pagerID, $insert);

        $this->view->title    = $this->lang->demand->common . $this->lang->hyphen . $this->lang->demand->showImport;
        $this->view->datas    = $demands;
        $this->view->backLink = $this->createLink('demand', 'browse', "poolID=$poolID");
        $this->view->poolID   = $poolID;

        $this->display('transfer', 'showImport');
    }

    /**
     * Export demands.
     *
     * @param  int    $poolID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($poolID, $orderBy)
    {
        if($_POST)
        {
            $this->session->set('demandTransferParams', array('poolID' => $poolID));

            $this->post->set('rows', $this->demand->getExportDemands($poolID, $orderBy));
            $this->post->set('kind', 'demand');
            $this->fetch('transfer', 'export', "model=demand");
        }

        $demandPool = $this->loadModel('demandpool')->getByID($poolID);
        $fileName   = $this->lang->demand->common . $this->lang->dash . $demandPool->name;

        $this->view->fileName        = $fileName;
        $this->view->allExportFields = $this->config->demand->exportFields;
        $this->view->selectedFields  = $this->config->demand->selectedFields;
        $this->view->customExport    = true;
        $this->display();
    }

    /**
     * 需求的父需求变更时，子需求确认变更。
     * Confirm the change of the parent story.
     *
     * @param  int    $objectID
     * @param  string $type
     * @access public
     * @return void
     */
    public function processDemandChange($objectID = 0, $type = 'demand')
    {
        if($type == 'demand')
        {
            $demand = $this->demand->fetchByID($objectID);
            $parent = $this->demand->fetchByID($demand->parent);

            $this->dao->update(TABLE_DEMAND)->set('parentVersion')->eq($parent->version)->where('id')->eq($objectID)->exec();
        }
        else
        {
            $story  = $this->loadModel('story')->getByID($objectID);
            $demand = $this->demand->fetchByID($story->demand);

            $this->dao->update(TABLE_STORY)->set('demandVersion')->eq($demand->version)->where('id')->eq($story->id)->exec();
        }
        return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
    }

    /**
     * 通过AJAX方式获取用户需要处理的需求。
     * AJAX: get stories of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id       the id of the select control.
     * @param  int    $appendID
     * @access public
     * @return string
     */
    public function ajaxGetUserDemands($userID = 0, $id = '', $appendID = 0)
    {
        if(empty($userID)) $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $demands = $this->demand->getUserDemands($user->account);

        $items = array();
        foreach($demands as $demandID => $demand) $items[] = array('text' => $demand->title, 'value' => $demandID);

        $fieldName = $id ? "demands[$id]" : 'demand';
        return print(json_encode(array('name' => $fieldName, 'items' => $items)));
    }

    /**
     * AJAX: Get parent demands by demand pool.
     *
     * @param  int    $poolID
     * @access public
     * @return void
     */
    public function ajaxGetParentDemands($poolID)
    {
        $parents = $this->demand->getParentDemandPairs($poolID);
        echo html::select('parent', empty($parents) ? array('' => '') : $parents, '', "class='form-control chosen'");
    }

    /**
     * AJAX: Get branches.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetBranches($productID = 0)
    {
        $branches = $this->loadModel('branch')->getPairs($productID, 'active');

        $branchList = array();
        foreach($branches as $branchID => $branchName) $branchList[] = array('text' => $branchName, 'value' => $branchID);

        return $this->send($branchList);
    }

    /**
     * AJAX: 根据需求池获取产品。
     * AJAX: Get products by demand pool.
     *
     * @param  int    $poolID
     * @access public
     * @return int
     */
    public function ajaxGetProducts($poolID)
    {
        $products = $this->loadModel('product')->getPairs('noclosed');

        $pool = $this->loadModel('demandpool')->getByID($poolID);
        if(!empty($pool->products)) $products = $this->product->getProductByPool($poolID, 'normal');

        $productList = array();
        foreach($products as $productID => $productName) $productList[] = array('value' => $productID, 'text' => $productName);

        return $this->send($productList);
    }

    /**
     * AJAX: Get assigned by demand pool.
     *
     * @param  int    $poolID
     * @access public
     * @return void
     */
    public function ajaxGetAssignedTo($poolID)
    {
        $users = $this->loadModel('demandpool')->getAssignedTo($poolID);
        return print(html::select('assignedTo', $users, '', "class='from-control picker-select'"));
    }

    /**
     * AJAX: Get story grade list by product ID.
     *
     * @param  int    $productID
     * @param  string $addProduct true|false
     * @access public
     * @return int
     */
    public function ajaxGetStoryGrade($productID, $addProduct = 'false')
    {
        $storyGrades = array();
        $gradeList   = array();
        $gradeList[] = array('text' => '', 'value' => '');

        if(!empty($productID))
        {
            $product     = $this->loadModel('product')->getByID($productID);
            $storyGrades = $this->demand->getStoryGradeList($product->status != 'normal' ? 'epic,requirement' : 'all', true);
        }

        if($addProduct == 'true') $storyGrades = $this->demand->getStoryGradeList('epic,requirement', true);

        foreach($storyGrades as $grade => $name) $gradeList[] = array('text' => $name, 'value' => $grade);
        return print(json_encode($gradeList));
    }

    /**
     * AJAX: Get roadmaps by product.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $param
     * @param  int    $charter
     * @access public
     * @return string
     */
    public function ajaxGetRoadmaps($productID = 0, $branch = '', $param = '', $charter = 0)
    {
        if(!$productID) return $this->send(array());

        $product = $this->loadModel('product')->getById($productID);
        if($product->type != 'normal' && $branch === '') return $this->send(array());

        $roadmaps = $this->loadModel('roadmap')->getPairs($productID, $branch, $param, $charter);
        $roadmaps = array_filter($roadmaps);

        $roadmapList = array();
        foreach($roadmaps as $roadmapID => $roadmapName) $roadmapList[] = array('text' => $roadmapName, 'value' => $roadmapID);

        return $this->send($roadmapList);
    }
}
