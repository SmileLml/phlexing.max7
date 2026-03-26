<?php
class roadmap extends control
{
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $products = $this->loadModel('product')->getPairs();
        if(empty($products) and !helper::isAjaxRequest()) $this->locate($this->createLink('product', 'create'));
    }

    /**
     * Roadmap gantt list.
     *
     * @param  int    $productID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $baselineID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $type = 'gantt', $orderBy = 'id_asc', $baselineID = 0)
    {
        $this->app->loadLang('programplan');
        $this->loadModel('execution');

        $products = $this->loadModel('product')->getPairs();
        $this->loadModel('product')->checkAccess($productID, $products);
        $this->product->setMenu($productID);
        $this->session->set('roadmapList', $this->app->getURI(true), 'product');

        $this->view->title       = $this->lang->roadmap->browse;
        $this->view->productID   = $productID;
        $this->view->product     = $this->product->getByID($productID);
        $this->view->roadmaps    = $this->roadmap->getDataForGantt($productID);
        $this->view->dateDetails = false;
        $this->view->ganttType   = $type;

        $this->display();
    }

    /**
     * Common actions.
     *
     * @param  int $productID
     * @param  int $branch
     *
     * @access public
     * @return void
     */
    public function commonAction($productID, $branch = 0)
    {
        $product = $this->loadModel('product')->getById($productID);
        if(empty($product)) $this->locate($this->createLink('product', 'create'));

        $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        $this->app->loadConfig('execution');
        if(!$product->shadow) $this->product->setMenu($productID, $branch);
        $this->session->set('currentProductType', $product->type);

        $branches = $this->loadModel('branch')->getList($productID, 0, 'all');
        $branchOption    = array();
        $branchTagOption = array();
        foreach($branches as $branchInfo)
        {
            $branchOption[$branchInfo->id]    = $branchInfo->name;
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }

        if($product->shadow)
        {
            $projectID = $this->dao->select('project')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($productID)->fetch('project');
            $this->loadModel('project')->setMenu($projectID);
        }

        $this->view->product         = $product;
        $this->view->projectID       = isset($projectID) ? $projectID : 0;
        $this->view->branch          = $branch;
        $this->view->branchOption    = $branchOption;
        $this->view->branchTagOption = $branchTagOption;
        $this->view->position[]      = html::a($this->createLink('product', 'browse', "productID={$productID}&branch=$branch"), $product->name);
    }

    /**
     * View roadmap.
     *
     * @param  int    $roadmapID
     * @param  string $type
     * @param  string $orderBy
     * @param  string $link
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     *
     * @access public
     * @return void
     */
    public function view($roadmapID = 0, $type = 'story', $orderBy = 'order_desc', $link = 'false', $param = '', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $roadmapID = (int)$roadmapID;
        $roadmap   = $this->roadmap->getByID($roadmapID);
        if(!$roadmap) return print(js::error($this->lang->notFound) . js::locate($this->createLink('product', 'all')));

        if($type == 'story' and ($orderBy != 'order_desc' or $pageID != 1 or $recPerPage != 100))
        {
            $this->session->set('storyList', $this->app->getURI(true), 'product');
        }
        else
        {
            $this->session->set('storyList', $this->createLink('roadmap', 'view', "roadmapID=$roadmapID&type=story"), 'product');
        }
        $this->session->set('roadmapList', $this->app->getURI(true), 'product');

        /* Determines whether an object is editable. */
        $canBeChanged = common::canBeChanged('roadmap', $roadmap);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;

        /* Append id for secend sort. */
        $sort    = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);

        $this->commonAction($roadmap->product, $roadmap->branch);
        $products = $this->product->getProductPairsByProject($this->session->project);

        $storyPager = new pager(0, $recPerPage, $type == 'story' ? $pageID : 1);

        /* Get stories of roadmap. */
        $this->loadModel('story');
        $roadmapStories = $this->roadmap->getRoadmapStories($roadmapID, 'all', $type == 'story' ? $sort : 'id_desc', $storyPager);

        $storyIdList = array();
        $modulePairs = $this->loadModel('tree')->getOptionMenu($roadmap->product, 'story', 0, 'all');
        foreach($roadmapStories as $story)
        {
            if(!isset($modulePairs[$story->module])) $modulePairs += $this->tree->getModulesName($story->module);
            $storyIdList[] = $story->id;
        }

        $this->loadModel('datatable');

        /* For action lang. */
        $product = $this->loadModel('product')->getByID($roadmap->product);
        $this->lang->roadmap->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        $gradeGroup = array();
        $gradeList  = $this->story->getGradeList('');
        foreach($gradeList as $grade) $gradeGroup[$grade->type][$grade->grade] = $grade->name;

        $this->view->title          = "ROADMAP #$roadmap->id $roadmap->name/" . zget($products, $roadmap->product, '');
        $this->view->modulePairs    = $modulePairs;
        $this->view->roadmapStories = $roadmapStories;
        $this->view->products       = $products;
        $this->view->summary        = $this->product->summary($this->view->roadmapStories, 'all');
        $this->view->roadmap        = $roadmap;
        $this->view->actions        = $this->loadModel('action')->getList('roadmap', $roadmapID);
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->modules        = $this->loadModel('tree')->getOptionMenu($roadmap->product);
        $this->view->type           = $type;
        $this->view->orderBy        = $orderBy;
        $this->view->link           = $link;
        $this->view->param          = $param;
        $this->view->storyPager     = $storyPager;
        $this->view->canBeChanged   = $canBeChanged;
        $this->view->storyCases     = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);;
        $this->view->gradeGroup     = $gradeGroup;
        $this->view->roadmaps       = array(0 => $this->lang->null) + $this->roadmap->getPairs($roadmap->product, 'all', 'linkRoadmap');

        $this->display();
    }

    /**
     * Ajax get can change roadmaps.
     *
     * @param  int    $product
     * @param  int    $roadmap
     * @param  string $branch
     * @access public
     * @return void
     */
    public function ajaxGetChangeRoadmaps($product, $roadmap, $branch)
    {
        $branch = explode(',', $branch);
        if(count($branch) == 1)
        {
            $branch = $branch[0] == 0 ? 'all' : $branch[0];
        }
        else if(count($branch) == 2 and in_array(0, $branch))
        {
            sort($branch);
            $branch = $branch[1];
        }
        else
        {
            $branch = '';
        }

        if(empty($branch)) return;

        $roadmap  = $this->roadmap->getByID($roadmap);
        $roadmaps = $this->roadmap->getPairs($product, $branch, 'wait,reject');

        unset($roadmaps['']);
        unset($roadmaps[$roadmap->id]);
        $roadmaps          = array(0 => $this->lang->null) + $roadmaps;
        $roadmapClass      = in_array($roadmap->status, array('launching', 'launched', 'closed')) ? "disabled" : 'dropdown-submenu';
        $changeRoadmapTips = sprintf($this->lang->roadmap->changeRoadmapTips, strtolower(zget($this->lang->roadmap->statusList, $roadmap->status)));
        $roadmapTips       = in_array($roadmap->status, array('launching', 'launched', 'closed')) ? "title='{$changeRoadmapTips}'" : '';
        foreach($roadmaps as $roadmapID => $roadmapName)
        {
            $actionLink = $this->createLink('story', 'batchChangeRoadmap', "roadmapID=$roadmapID");
            echo "<li class='option' data-key='$roadmapID'>" . html::a('#', $roadmapName, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', this)\"") . "</li>";
        }
    }

    /**
     * Delete demandpool.
     *
     * @param mixed $roadmapID
     * @param string $confirm
     * @access public
     * @return int
     */
    public function delete($roadmapID)
    {
        $roadmap = $this->roadmap->getByID($roadmapID);
        if(!in_array($roadmap->status, array('wait', 'closed', 'reject'))) return $this->send(array('result' => 'success', 'load' => array('alert' => sprintf($this->lang->roadmap->deleteRoadmapTips, zget($this->lang->roadmap->statusList, $roadmap->status)), 'locate' => array('load' => true))));

        $this->roadmap->delete(TABLE_ROADMAP, $roadmapID);

        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));

        $locateLink = $this->createLink('roadmap', 'browse', "productID={$roadmap->product}");
        return $this->sendSuccess(array('load' => $locateLink));
    }

    /**
     * Unlink story
     *
     * @param  int    $storyID
     * @param  int    $roadmapID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function unlinkUR($storyID, $roadmapID, $confirm = 'no')
    {
        $story = $this->loadModel('story')->getByID($storyID);
        if($confirm == 'no')
        {
            $confirmTip = $story->stage == 'incharter' ? $this->lang->roadmap->confirmUnlinkLaunchedStory : $this->lang->roadmap->confirmUnlinkStory;
            $confirmURL = inlink('unlinkUR', "storyID=$storyID&roadmapID=$roadmapID&confirm=yes");
            $cancelURL  = inlink('view', "roadmapID=$roadmapID");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm('{$confirmTip}').then((res) => {if(res) {openUrl('{$confirmURL}', {load: 'modal', size: 'lg'});} else {openUrl('{$cancelURL}');}});"));
        }

        if($_POST || $story->stage != 'incharter')
        {
            $this->roadmap->unlinkUR($storyID, $roadmapID);
            $this->loadModel('action')->create('roadmap', $roadmapID, 'unlinkur', '', $storyID);

            return $this->sendSuccess(array('load' => $this->session->storyList ? $this->session->storyList : inlink('view', "roadmapID=$roadmapID&type=story"), 'closeModal' => true));
        }

        $this->story->getAffectedScope($story);

        $story->stories = isset($story->stories) ? $story->stories : array();
        $story->bugs    = isset($story->bugs)    ? $story->bugs    : array();
        $story->cases   = isset($story->cases)   ? $story->cases   : array();

        $this->app->loadLang('task');
        $this->app->loadLang('testcase');
        $this->app->loadLang('product');

        $this->view->title   = $this->lang->roadmap->unlinkUR;
        $this->view->story   = $story;
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->view->actions = $this->loadModel('action')->getList('story', $storyID);
        $this->display();
    }

    /**
     * Batch unlink UR.
     *
     * @param  int    $roadmapID
     * @access public
     * @return int
     */
    public function batchUnlinkUR($roadmapID)
    {
        $storyIdList       = $this->post->storyIdList;
        $failedStoryIdList = array();
        foreach($storyIdList as $index => $storyID)
        {
            $result = $this->roadmap->unlinkUR($storyID, $roadmapID);

            if(!$result)
            {
                $failedStoryIdList[] = $storyID;
                unset($storyIdList[$index]);
            }
        }
        if($storyIdList) $this->loadModel('action')->create('roadmap', $roadmapID, 'unlinkur', '', implode(',', $storyIdList));

        $failedRemoveTip = !empty($failedStoryIdList) ? $this->lang->roadmap->failedRemoveTip : '';
        return $this->send(array('result' => 'success', 'load' => array('alert' => $failedRemoveTip, 'locate' => array('load' => true))));
    }

    /**
     * Link stories.
     *
     * @param int    $roadmapID
     * @param string $browseType
     * @param int    $param
     * @param string $orderBy
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     *
     * @access public
     * @return void
     */
    public function linkUR($roadmapID = 0, $browseType = '', $param = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(!empty($_POST['stories']))
        {
            $this->roadmap->linkUR($roadmapID);
            if($this->viewType == 'json') return $this->send(array('result' => 'success'));
            return $this->send(array('result' => 'success', 'load' => inlink('view', "roadmapID=$roadmapID&type=story&orderBy=$orderBy")));
        }

        $this->session->set('storyList', inlink('view', "roadmapID=$roadmapID&type=story&orderBy=$orderBy&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'product');

        $this->loadModel('story');
        $this->loadModel('requirement');
        $this->loadModel('tree');
        $roadmap = $this->roadmap->getByID($roadmapID);
        $this->commonAction($roadmap->product, $roadmap->branch);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->config->product->search['fields']['title'] = $this->lang->roadmap->storyName;
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;
        unset($this->config->product->search['fields']['product']);
        $this->config->product->search['actionURL'] = $this->createLink('roadmap', 'view', "roadmapID=$roadmapID&type=story&orderBy=$orderBy&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['style']     = 'simple';
        $this->config->product->search['params']['roadmap']['values'] = $this->roadmap->getPairsForStory($roadmap->product, $roadmap->branch, 'withMainRoadmap');
        $this->config->product->search['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($roadmap->product, 'story', 0, 'all');
        $this->config->product->search['params']['stage']['values']   = $this->lang->requirement->stageList;

        unset($this->config->product->search['fields']['status']);
        unset($this->config->product->search['fields']['feedbackBy']);
        unset($this->config->product->search['fields']['notifyEmail']);
        unset($this->config->product->search['fields']['closedBy']);
        unset($this->config->product->search['fields']['closedReason']);
        unset($this->config->product->search['fields']['closedDate']);

        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->config->product->search['fields']['branch'] = $this->lang->product->branch;

            $branchPairs = $this->dao->select('id, name')->from(TABLE_BRANCH)->where('id')->in($roadmap->branch)->fetchPairs();
            $branches   = array('' => '', BRANCH_MAIN => $this->lang->branch->main) + $branchPairs;
            $this->config->product->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->product->search);

        $roadmapStories = $this->roadmap->getExcludeStories($roadmap);

        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($roadmap->product, "0,{$roadmap->branch}", $queryID, 'id_desc', 0, $this->config->enableER ? 'all' : 'requirement', array_keys($roadmapStories), '', $pager);
        }
        else
        {
            $allStories = $this->story->getProductStories($this->view->product->id, $roadmap->branch ? "0,{$roadmap->branch}" : 0, '0', 'active', $this->config->enableER ? 'all' : 'requirement', 'id_desc', true, array_keys($roadmapStories), $pager);
        }

        $modules = $this->loadModel('tree')->getOptionMenu($roadmap->product, 'story', 0, 'all');
        foreach($allStories as $storyID => &$story)
        {
            if($story->type == 'story') unset($allStories[$storyID]);
            if($story->isParent > 0) $story->children = $this->story->getAllChildId($storyID, false);
            if(!isset($modules[$story->module])) $modules += $this->tree->getModulesName($story->module);
        }

        $allStories = $this->roadmap->mergeRoadmapTitle($roadmap->product, $allStories);

        $gradeGroup = array();
        $gradeList  = $this->story->getGradeList('');
        foreach($gradeList as $grade) $gradeGroup[$grade->type][$grade->grade] = $grade->name;

        $this->view->allStories  = $allStories;
        $this->view->roadmap     = $roadmap;
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType  = $browseType;
        $this->view->modules     = $modules;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->gradeGroup  = $gradeGroup;
        $this->display();
    }

    /**
     * Create a roadmap.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function create($productID = 0, $branchID = 0)
    {
        $this->loadModel('product')->setMenu($productID);
        $product  = $this->product->getByID($productID);
        $branches = $product->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array();

        if($_POST)
        {
            $roadmapID = $this->roadmap->create($productID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action')->create('roadmap', $roadmapID, 'created');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('browse', "productID=$productID");

            if(isonlybody())
            {
                unset($response['locate']);
                $response['closeModal'] = true;
                $response['callback']   = "parent.loadProductRoadmaps($productID, $branchID)";
            }

            $this->send($response);
        }

        $this->view->title     = $this->lang->roadmap->browse;
        $this->view->productID = $productID;
        $this->view->product   = $this->product->getByID($productID);
        $this->view->branches  = $branches;
        $this->display();
    }

    /**
     * Sort story for roadmap.
     *
     * @param int $roadmapID
     *
     * @access public
     * @return bool
     */
    public function ajaxStorySort($roadmapID = 0)
    {
        if(empty($roadmapID)) return true;

        /* Get story id list. */
        $storyIdList = json_decode($this->post->stories, true);
        asort($storyIdList);

        /* Update the story order according to the plan. */
        $this->loadModel('story')->sortStoriesOfRoadmap($roadmapID, array_flip($storyIdList), $this->post->orderBy, $this->post->pageID, $this->post->recPerPage);

        return $this->send(array('result' => 'success'));
    }

    public function edit($roadmapID = 0)
    {
        $roadmap = $this->roadmap->getByID($roadmapID);
        $this->loadModel('product')->setMenu($roadmap->product);

        $product  = $this->product->getByID($roadmap->product);
        $branches = $product->type != 'normal' ? $this->loadModel('branch')->getPairs($roadmap->product, 'active') : array();

        if($_POST)
        {
            $changes = $this->roadmap->update($roadmapID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('roadmap', $roadmapID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('browse', "productID=$roadmap->product");

            $this->send($response);
        }

        $this->view->title    = $this->lang->roadmap->browse;
        $this->view->product  = $product;
        $this->view->roadmap  = $roadmap;
        $this->view->branches = $branches;
        $this->display();
    }

    /**
     * Close a roadmap.
     *
     * @param  int    $roadmapID
     * @access public
     * @return void
     */
    public function close($roadmapID = 0)
    {
        $roadmap = $this->roadmap->getByID($roadmapID);
        if($_POST)
        {
            $changes = $this->roadmap->close($roadmapID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($changes || $this->post->comment != '')
            {
                $actionID = $this->loadModel('action')->create('roadmap', $roadmapID, 'closed', $this->post->comment, $roadmap->status);
                $this->action->logHistory($actionID, $changes);
            }

            return print(js::reload('parent.parent'));
        }

        $this->view->title    = $this->lang->roadmap->close;
        $this->view->roadmap  = $roadmap;
        $this->view->users    = $this->loadModel('user')->getPairs('nodeleted');
        $this->view->actions  = $this->loadModel('action')->getList('roadmap', $roadmapID);
        $this->display();
    }

    /**
     * Activate a roadmap.
     *
     * @param  int    $roadmapID
     * @param  string $confirm   yes|no
     * @access public
     * @return int
     */
    public function activate($roadmapID)
    {
        $changes = $this->roadmap->activate($roadmapID);
        if(dao::isError()) return $this->sendError(dao::getError());

        if($changes)
        {
            $actionID = $this->loadModel('action')->create('roadmap', $roadmapID, 'activated');
            $this->action->logHistory($actionID, $changes);
        }

        return $this->send(array('result' => 'success', 'load' => $this->session->roadmapList));
    }

    /**
     * Ajax get notice for edit page.
     *
     * @param  int    $roadmapID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function ajaxGetNotice($roadmapID, $branch)
    {
        if($branch != 0)
        {
            $storyID = $this->dao->select('t2.id')->from(TABLE_ROADMAPSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->where('t1.roadmap')->eq($roadmapID)
                ->andWhere('t2.branch')->ne($branch)
                ->andWhere('t2.branch')->ne(0)
                ->fetch('id');

            if($storyID) die(true);
        }

        die(false);
    }
}
