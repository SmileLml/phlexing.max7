<?php
class projectplan extends control
{
    /**
     * 项目计划列表。
     * Browse plans.
     *
     * @param  int    $productID
     * @param string $branch
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $branch = '', $browseType = 'undone', $queryID = 0, $orderBy = 'begin_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1 )
    {
        echo $this->fetch('productplan', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&queryID=$queryID&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * 创建一个计划。
     * Create a plan.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  int    $parent
     * @access public
     * @return void
     */
    public function create($productID = 0, $branchID = 0, $parent = 0)
    {
        echo $this->fetch('productplan', 'create', "productID=$productID&branchID=$branchID&parent=$parent");
    }

    /**
     * 编辑一个计划。
     * Edit a plan.
     *
     * @param int $planID
     * @access public
     * @return void
     */
    public function edit($planID)
    {
        echo $this->fetch('productplan', 'edit', "planID={$planID}");
    }

    /**
     * 查看项目计划。
     * View plan.
     *
     * @param  int    $planID
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
    public function view($planID = 0, $type = 'story', $orderBy = 'order_desc', $link = 'false', $param = '', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        echo $this->fetch('productplan', 'view', "planID=$planID&type=$type&orderBy=$orderBy&link=$link&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * 关联需求。
     * Link stories.
     *
     * @param  int    $planID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory($planID = 0, $browseType = '', $param = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        echo $this->fetch('productplan', 'linkStory', "planID={$planID}&browseType={$browseType}&param={$param}&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
    }

    /**
     * 计划管理Bug列表。
     * Link bug list.
     *
     * @param  int    $planID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBug($planID = 0, $browseType = '', $param = '0', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        echo $this->fetch('productplan', 'linkBug', "planID={$planID}&browseType={$browseType}&param={$param}&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
    }
}
