<?php
class demandpool extends control
{
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('story');
    }

    /**
     * Browse demandpool list.
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
    public function browse($browseType = 'mine', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        unset($this->lang->demandpool->menu);

        $this->loadModel('demand');
        $browseType = strtolower($browseType);

        $this->session->set('demandpoolList', $this->app->getURI(true));

        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('demandpool', 'browse', "browseType=bySearch&param=myQueryID");
        $this->demandpool->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title        = $this->lang->demandpool->browse;
        $this->view->demandpools  = $this->demandpool->getList($browseType, $queryID, $orderBy, $pager);
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->browseType   = $browseType;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->depts        = $this->loadModel('dept')->getOptionMenu();
        $this->display();
    }

    /**
     * Create a demandpool.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        unset($this->lang->demandpool->menu);

        if($_POST)
        {
            $poolID = $this->demandpool->create();

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action')->create('demandpool', $poolID, 'created');
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('browse');

            $this->send($response);
        }

        $this->view->title     = $this->lang->demandpool->create;
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed');
        $this->view->products  = $this->loadModel('product')->getPairs('noclosed');
        $this->display();
    }

    /**
     * Edit a demandpool.
     *
     * @param  int $poolID
     * @access public
     * @return void
     */
    public function edit($poolID = 0)
    {
        $this->demandpool->setMenu($poolID);

        if($_POST)
        {
            $changes = $this->demandpool->update($poolID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('demandpool', $poolID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $locate  = inlink('view', "poolID=$poolID");
            $account = $this->app->user->account;

            if(!empty($_POST['reviewer']) and !in_array($account, $_POST['reviewer'])) $locate = inlink('browse');
            if(!empty($_POST['owner']) and !in_array($account, $_POST['owner'])) $locate = inlink('browse');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = $locate;

            $this->send($response);
        }

        $this->view->title      = $this->lang->demandpool->edit;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->demandpool = $this->demandpool->getByID($poolID);
        $this->view->depts      = $this->loadModel('dept')->getOptionMenu();
        $this->view->products   = $this->loadModel('product')->getPairs('noclosed');

        $this->display();
    }

    /**
     * View a demandpool.
     *
     * @param  int    $poolID
     * @access public
     * @return void
     */
    public function view($poolID = 0)
    {
        $demandpool = $this->loadModel('demandpool')->getByID($poolID);
        if(!$demandpool) return print(js::error($this->lang->notFound) . js::locate($this->createLink('demandpool', 'browse')));

        $this->demandpool->setMenu($poolID);

        $this->view->title      = $this->lang->demandpool->view;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->actions    = $this->loadModel('action')->getList('demandpool', $poolID);
        $this->view->demandpool = $this->loadModel('demandpool')->getByID($poolID);
        $this->view->depts      = $this->loadModel('dept')->getOptionMenu();
        $this->view->products   = $this->loadModel('product')->getPairs('noclosed');

        $this->display();
    }

    /**
     * Demand pool track.
     *
     * @param  int         $poolID
     * @param  string      $browseType
     * @param  int         $param
     * @param  string      $orderBy
     * @param  int         $recTotal
     * @param  int         $recPerPage
     * @param  int         $pageID
     * @access public
     * @return void
     * @param string $storyType
     */
    public function track($poolID, $browseType = 'all', $storyType = 'demand', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('demand');

        $poolID = $this->demandpool->setMenu($poolID);

        $browseType = strtolower($browseType);

        $this->session->set('demandList', $this->app->getURI(true), 'demandpool');
        $this->session->set('storyList', $this->app->getURI(true), 'demandpool');

        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('demandpool', 'track', "poolID=$poolID&browseType=bySearch&param=myQueryID");
        $this->demand->buildSearchForm($poolID, $queryID, $actionURL);

        /* Load pager and get tracks. */
        $this->app->loadClass('pager', true);
        $pager   = new pager($recTotal, $recPerPage, $pageID);
        $demands = $this->demand->getList($poolID, $browseType, $queryID, $orderBy, $pager, '', false, '', "id,pool,product,parent,pri,title,assignedTo,status,stage,childDemands,'demand' as storyType");

        $parentIdList = array_filter(array_unique(array_column($demands, 'parent')));
        $parents      = $this->demand->getByList($parentIdList, "id,pool,product,parent,pri,title,assignedTo,status,stage,childDemands,'demand' as storyType");
        $demands     += $parents;
        $demands      = $this->demand->mergeChildrenForTrack($demands);

        $tracks = $this->demand->getTracks($demands, $storyType);

        $customFields = $this->demandpoolZen->getCustomFieldsForTrack($storyType);
        $mergeCells   = $this->story->getMergeTrackCells($tracks, $customFields['show']);
        if(isset($tracks['cols']))
        {
            $showFields = $customFields['show'];
            $cols       = array();
            foreach($tracks['cols'] as $col)
            {
                if(in_array($col['name'], $showFields) || (isset($col['parentName']) && in_array($col['parentName'], $showFields))) $cols[] = $col;
            }
            $tracks['cols'] = $cols;
        }

        $this->view->title        = $this->lang->story->track;
        $this->view->tracks       = $tracks;
        $this->view->pager        = $pager;
        $this->view->poolID       = $poolID;
        $this->view->browseType   = $browseType;
        $this->view->orderBy      = $orderBy;
        $this->view->customFields = $customFields;
        $this->view->mergeCells   = $mergeCells;
        $this->view->param        = $param;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->display();
    }

    /**
     * Close a demandpool.
     *
     * @param  int    $poolID
     * @access public
     * @return void
     */
    public function close($poolID = 0)
    {
        if($_POST)
        {
            $changes = $this->demandpool->close($poolID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            if($changes || $this->post->comment != '')
            {
                $actionID = $this->loadModel('action')->create('demandpool', $poolID, 'closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = 'parent';

            $this->send($response);
        }

        $this->view->title      = $this->lang->demandpool->close;
        $this->view->demandpool = $this->demandpool->getByID($poolID);
        $this->view->users      = $this->loadModel('user')->getPairs('nodeleted');
        $this->view->actions    = $this->loadModel('action')->getList('demandpool', $poolID);
        $this->display();
    }

    /**
     * Activate a demandpool.
     *
     * @param  int    $poolID
     * @access public
     * @return void
     */
    public function activate($poolID = 0)
    {
        if($_POST)
        {
            $changes = $this->demandpool->activate($poolID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            if($changes || $this->post->comment != '')
            {
                $actionID = $this->loadModel('action')->create('demandpool', $poolID, 'activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = 'parent';

            $this->send($response);
        }

        $this->view->title      = $this->lang->demandpool->activate;
        $this->view->demandpool = $this->demandpool->getByID($poolID);
        $this->view->users      = $this->loadModel('user')->getPairs('nodeleted');
        $this->view->actions    = $this->loadModel('action')->getList('demandpool', $poolID);
        $this->display();
    }

    /**
     * Delete demandpool.
     *
     * @param  int    $poolID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($poolID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $demand = $this->dao->select('id')->from(TABLE_DEMAND)->where('pool')->eq($poolID)->andWhere('deleted')->eq('0')->fetch('id');
            if($demand)
            {
                echo js::alert($this->lang->demandpool->hasDemand);
                die(js::reload('parent'));
            }

            echo js::confirm($this->lang->demandpool->confirmDelete, $this->createLink('demandpool', 'delete', "demandpool=$poolID&confirm=yes"), '');
            exit;
        }
        else
        {
            $this->demandpool->delete(TABLE_DEMANDPOOL, $poolID);

            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));

            $locateLink = $this->createLink('demandpool', 'browse');
            die(js::locate($locateLink, 'parent'));
        }
    }

    /**
     * Ajax get drop menu.
     *
     * @param  int    $poolID
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($poolID, $module, $method)
    {
        $this->view->link        = $this->demandpool->getPoolLink($module, $method);
        $this->view->poolID      = $poolID;
        $this->view->demandpools = $this->demandpool->getList();
        $this->view->module      = $module;
        $this->view->method      = $method;
        $this->display();
    }

    /**
     * Ajax get old drop menu.
     *
     * @param  int    $poolID
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function ajaxGetOldDropMenu($poolID, $module, $method)
    {
        $this->view->link        = $this->demandpool->getPoolLink($module, $method);
        $this->view->poolID      = $poolID;
        $this->view->demandpools = $this->demandpool->getList();
        $this->view->module      = $module;
        $this->view->method      = $method;
        $this->display();
    }

    /**
     * Ajax check reviewer.
     *
     * @param  int    $poolID
     * @param  string $account
     * @access public
     * @return void
     */
    public function ajaxCheckReviewer($poolID, $account)
    {
        $reviewingDemand = $this->dao->select('id')->from(TABLE_DEMAND)->alias('t1')
            ->leftJoin(TABLE_DEMANDREVIEW)->alias('t2')
            ->on("t1.id = t2.demand")
            ->where('t1.pool')->eq($poolID)
            ->andWhere('t1.status')->eq('reviewing')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.reviewer')->eq($account)
            ->andWhere('t2.result')->eq('')
            ->fetch('id');

        $reviewingDemand = $reviewingDemand ? true : false;
        die($reviewingDemand);
    }
}
