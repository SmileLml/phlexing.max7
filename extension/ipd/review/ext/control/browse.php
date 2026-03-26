<?php
helper::importControl('review');
class myReview extends review
{
    /**
     * Browse reviews.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     * @param int $param
     */
    public function browse($projectID = 0, $browseType = 'all', $orderBy = 't1.id_desc', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->commonAction($projectID);
        $this->loadModel('datatable');
        $this->session->set('reviewList', $this->app->getURI(true), 'project');
        $browseType = strtolower($browseType);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL  = $this->createLink('review', 'browse', "projectID=$projectID&browseType=bysearch&orderBy=$orderBy&param=myQueryID");
        $this->review->buildSearchForm($actionURL, $queryID, $projectID);

        $reviewList = $this->review->getList($projectID, $browseType, $orderBy, $pager, $queryID);

        $pointLatestReviews = $this->review->getPointLatestReviews($projectID);
        foreach($reviewList as $review) $review->latestReview = isset($pointLatestReviews[$review->category]) ? $pointLatestReviews[$review->category]->id : $review->id;

        $this->view->title = $this->lang->review->browse;

        $this->view->reviewList     = $reviewList;
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|noletter|all');
        $this->view->pager          = $pager;
        $this->view->param          = $param;
        $this->view->recTotal       = $recTotal;
        $this->view->recPerPage     = $recPerPage;
        $this->view->pageID         = $pageID;
        $this->view->orderBy        = $orderBy;
        $this->view->browseType     = $browseType;
        $this->view->products       = $this->loadModel('product')->getPairs($projectID);
        $this->view->projectID      = $projectID;
        $this->view->project        = $this->loadModel('project')->getByID($projectID);
        $this->view->pendingReviews = $this->loadModel('approval')->getPendingReviews('review');
        $this->view->reviewers      = $this->review->getReviewerByIdList(array_keys($reviewList));

        $this->display();
    }
}
