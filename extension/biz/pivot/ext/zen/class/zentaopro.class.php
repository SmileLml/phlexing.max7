<?php
class zentaoproPivotZen extends pivotZen
{
    /**
     * Product roadmap.
     *
     * @param  string $conditions
     * @access public
     * @return void
     */
    public function roadmap($conditions = '')
    {
        $this->app->loadConfig('productplan');
        $roadmaps = $this->pivot->getRoadmaps($conditions);

        $this->view->title       = $this->lang->pivot->roadmap;
        $this->view->pivotName   = $this->lang->pivot->roadmap;
        $this->view->products    = $roadmaps['products'];
        $this->view->plans       = $roadmaps['plans'];
        $this->view->submenu     = 'product';
        $this->view->conditions  = $conditions;
        $this->view->currentMenu = 'roadmap';
    }

    /**
     * Product invest pivot.
     *
     * @access public
     * @return void
     */
    public function productInvest($conditions = '', $productID = 0, $productStatus = 'normal', $productType = 'normal')
    {
        $this->app->loadLang('story');
        $this->app->loadLang('product');
        $this->app->loadLang('productplan');

        $filters = array('productID' => $productID, 'productStatus' => $productStatus, 'productType' => $productType);

        $this->view->productID     = $productID;
        $this->view->productStatus = $productStatus;
        $this->view->productType   = $productType;
        $this->view->statusList    = $this->lang->product->statusList;
        $this->view->typeList      = array_filter($this->lang->product->typeList);
        $this->view->products      = $this->loadModel('bi')->getScopeOptions('product');

        $this->view->title       = $this->lang->pivot->productInvest;
        $this->view->pivotName   = $this->lang->pivot->productInvest;
        $this->view->investData  = $this->pivot->getProductInvest($conditions, $filters);
        $this->view->submenu     = 'product';
        $this->view->conditions  = $conditions;
        $this->view->currentMenu = 'productInvest';
    }

    /**
     * Test case statistics table.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function testcase($productID = 0)
    {
        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        if(!$productID) $productID = key($products);

        $this->app->loadLang('testcase');
        $this->view->title       = $this->lang->pivot->testcase;
        $this->view->pivotName   = $this->lang->pivot->testcase;
        $this->view->products    = $products;
        $this->view->productID   = $productID;
        $this->view->modules     = $this->pivot->getTestcases($productID);
        $this->view->submenu     = 'test';
        $this->view->currentMenu = 'testcase';
    }

    /**
     * Use case execution statistics table.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function casesrun($productID = 0)
    {
        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        if(!$productID) $productID = key($products);

        $this->app->loadLang('testcase');
        $this->view->title       = $this->lang->pivot->casesrun;
        $this->view->pivotName   = $this->lang->pivot->casesrun;
        $this->view->products    = $products;
        $this->view->productID   = $productID;
        $this->view->modules     = $this->pivot->getCasesRun($productID);
        $this->view->submenu     = 'test';
        $this->view->currentMenu = 'casesrun';
    }

    /**
     * Version statistics table.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function build($productID = 0)
    {
        $this->app->loadLang('bug');

        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        if(!$productID) $productID = key($products);

        $projectID = $this->lang->navGroup->pivot == 'project' ? $this->session->project : 0;
        $projectID = is_numeric($projectID) ? $projectID : 0;
        $productID = is_numeric($productID) ? $productID : 0;
        $buildBugs = $this->pivot->getBuildBugs($productID);

        $this->view->title       = $this->lang->pivot->build;
        $this->view->pivotName   = $this->lang->pivot->build;
        $this->view->products    = $products;
        $this->view->productID   = $productID;
        $this->view->bugs        = $buildBugs['bugs'];
        $this->view->summary     = $buildBugs['summary'];
        $this->view->projects    = $this->loadModel('product')->getProjectPairsByProduct($productID);
        $this->view->builds      = $this->loadModel('build')->getBuildPairs(array($productID), 'all', '');
        $this->view->submenu     = 'test';
        $this->view->currentMenu = 'build';
    }

    /**
     * Story related bug summary table.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function storyLinkedBug($productID = 0, $moduleID = 0)
    {
        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        if(!$productID) $productID = key($products);
        $productID = is_numeric($productID) ? $productID : 0;

        $this->app->loadLang('bug');
        $this->view->title       = $this->lang->pivot->storyLinkedBug;
        $this->view->pivotName   = $this->lang->pivot->storyLinkedBug;
        $this->view->products    = $products;
        $this->view->modules     = arrayUnion(array('/'), $this->loadModel('tree')->getOptionMenu($productID, 'story', 0, 'all'));
        $this->view->productID   = $productID;
        $this->view->moduleID    = $moduleID;
        $this->view->stories     = $this->pivot->getStoryBugs($productID, $moduleID);
        $this->view->submenu     = 'test';
        $this->view->currentMenu = 'storylinkedbug';
    }

    /**
     * Work summary.
     *
     * @param  int    $begin
     * @param  int    $end
     * @param  int    $dept
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function workSummary($begin = 0, $end = 0, $dept = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadLang('task');
        $begin = $begin ? date('Y-m-d', strtotime($begin)) : date('Y-m-d', strtotime('last month', strtotime(date('Y-m',time()) . '-01 00:00:01')));
        $end   = $end   ? date('Y-m-d', strtotime($end))   : date('Y-m-d', strtotime('now'));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title     = $this->lang->pivot->workSummary;
        $this->view->pivotName = $this->lang->pivot->workSummary;

        $this->view->users       = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->depts       = $this->loadModel('dept')->getOptionMenu();
        $this->view->projects    = $this->loadModel('project')->getPairsByProgram();
        $this->view->executions  = $this->loadModel('execution')->getPairs(0, 'all', 'all');
        $this->view->begin       = $begin;
        $this->view->end         = $end;
        $this->view->dept        = $dept;
        $this->view->userTasks   = $this->pivot->getWorkSummary($begin, $end, $dept, 'worksummary', $pager);
        $this->view->pager       = $pager;
        $this->view->submenu     = 'staff';
        $this->view->currentMenu = 'worksummary';
    }

    /**
     * Task assignment summary.
     *
     * @param  date   $begin
     * @param  date   $end
     * @param  int    $dept
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function workAssignSummary($begin = 0, $end = 0, $dept = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadLang('task');
        $begin = $begin ? date('Y-m-d', strtotime($begin)) : date('Y-m-d', strtotime('last month', strtotime(date('Y-m',time()) . '-01 00:00:01')));
        $end   = $end   ? date('Y-m-d', strtotime($end))   : date('Y-m-d', strtotime('now'));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->pivot->workAssignSummary;
        $this->view->pivotName  = $this->lang->pivot->workAssignSummary;

        $this->view->users       = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->depts       = $this->loadModel('dept')->getOptionMenu();
        $this->view->projects    = $this->loadModel('project')->getPairsByProgram();
        $this->view->executions  = $this->loadModel('execution')->getPairs();
        $this->view->begin       = $begin;
        $this->view->end         = $end;
        $this->view->dept        = $dept;
        $this->view->userTasks   = $this->pivot->getWorkSummary($begin, $end, $dept, 'workassignsummary', $pager);
        $this->view->pager       = $pager;
        $this->view->submenu     = 'staff';
        $this->view->currentMenu = 'workAssignSummary';
    }

    /**
     * Bug resolution summary table.
     *
     * @param  int    $dept
     * @param  int    $begin
     * @param  int    $end
     * @access public
     * @return void
     */
    public function bugSummary($dept = 0, $begin = 0 , $end = 0)
    {
        $this->app->loadLang('bug');
        $begin = $begin ? date('Y-m-d', strtotime($begin)) : date('Y-m-d', strtotime('last month', strtotime(date('Y-m',time()) . '-01 00:00:01')));
        $end   = $end   ? date('Y-m-d', strtotime($end))   : date('Y-m-d', strtotime('now'));

        $this->view->title      = $this->lang->pivot->bugSummary;
        $this->view->pivotName  = $this->lang->pivot->bugSummary;

        $this->view->users       = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->depts       = $this->loadModel('dept')->getOptionMenu();
        $this->view->dept        = $dept;
        $this->view->begin       = $begin;
        $this->view->end         = $end;
        $this->view->userBugs    = $this->pivot->getBugSummary($dept, $begin, $end, 'bugsummary');
        $this->view->submenu     = 'staff';
        $this->view->currentMenu = 'bugsummary';
    }

    /**
     * Summary of Bug Assignment.
     *
     * @param  int    $dept
     * @param  int    $begin
     * @param  int    $end
     * @access public
     * @return void
     */
    public function bugAssignSummary($dept = 0, $begin = 0 , $end = 0)
    {
        $this->app->loadLang('bug');
        $begin = $begin ? date('Y-m-d', strtotime($begin)) : date('Y-m-d', strtotime('last month', strtotime(date('Y-m',time()) . '-01 00:00:01')));
        $end   = $end   ? date('Y-m-d', strtotime($end))   : date('Y-m-d', strtotime('now'));

        $this->view->title      = $this->lang->pivot->bugAssignSummary;
        $this->view->pivotName  = $this->lang->pivot->bugAssignSummary;

        $this->view->users       = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->depts       = $this->loadModel('dept')->getOptionMenu();
        $this->view->dept        = $dept;
        $this->view->begin       = $begin;
        $this->view->end         = $end;
        $this->view->userBugs    = $this->pivot->getBugSummary($dept, $begin, $end, 'bugassignsummary');
        $this->view->submenu     = 'staff';
        $this->view->currentMenu = 'bugAssignSummary';
    }
}
