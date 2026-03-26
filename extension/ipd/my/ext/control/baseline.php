<?php
class myMy extends my
{
    /**
     * Baseline list.
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
    public function baseline($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadLang('baseline');

        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title        = $this->lang->baseline->common;
        $this->view->browseType   = $browseType;
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->mode         = 'baseline';
        $this->view->baselines    = $this->my->getBaselineList($browseType, $orderBy, $pager);
        $this->view->projectPairs = $this->loadModel('project')->getPairsByProgram();
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed|noletter|all');

        $this->display();
    }
}
