<?php
class myMy extends my
{
    /**
     * Audit list.
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
    public function myAudit($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('review');
        $this->loadModel('approval');
        $this->app->loadLang('baseline');

        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title        = $this->lang->review->audit;
        $this->view->browseType   = $browseType;
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->mode         = 'myaudit';
        $this->view->projectPairs = $this->loadModel('project')->getPairsByProgram();;
        $this->view->productPairs = $this->loadModel('product')->getPairs();
        $this->view->ipdProjects  = $this->project->getPairsByModel('ipd');
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|noletter|all');
        $this->view->reviewList   = $this->my->getAuditList($browseType, $orderBy, $pager);

        $this->display();
    }
}
