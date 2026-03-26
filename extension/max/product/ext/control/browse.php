<?php
helper::importControl('product');
class myProduct extends product
{
    /**
     * 浏览产品研发/用户需求列表。
     * Browse requirements list of product.
     *
     * @param  int     $productID
     * @param  string  $branch      all|''|0
     * @param  string  $browseType
     * @param  int     $param       Story Module ID
     * @param  string  $storyType   requirement|story
     * @param  string  $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @param  int     $projectID
     * @param  string  $from
     * @access public
     * @return void
     * @param int $blockID
     */
    public function browse($productID = 0, $branch = 'all', $browseType = '', $param = 0, $storyType = 'story', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1, $projectID = 0, $from = 'product', $blockID = 0)
    {
        $this->app->loadLang('projectstory');
        $this->view->approvers = $this->loadModel('assetlib')->getApproveUsers();
        $this->view->libs      = $this->assetlib->getPairs('story');
        parent::browse($productID, $branch, $browseType, $param, $storyType, $orderBy, $recTotal, $recPerPage, $pageID, $projectID, $from, $blockID);
    }
}
