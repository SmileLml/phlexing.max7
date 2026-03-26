<?php
class myMy extends my
{
    /**
     * Meeting list.
     *
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function meeting($browseType = 'all', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('meeting');

        $uri = $this->app->getURI(true);
        $this->session->set('meetingList', $uri, 'my');


        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('my', 'meeting', "browseType=bysearch&queryID=myQueryID");
        $this->meeting->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $meetings = $this->meeting->getListByUser($browseType, $orderBy, $param, $pager);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'meeting', false);

        $this->view->title       = $this->lang->my->common . $this->lang->hyphen . $this->lang->my->meeting;
        $this->view->meetings    = $meetings;
        $this->view->browseType  = $browseType;
        $this->view->param       = $param;
        $this->view->projectID   = 0;
        $this->view->executionID = 0;
        $this->view->from        = 'my';
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->depts       = $this->loadModel('dept')->getOptionMenu();
        $this->view->users       = $this->loadModel('user')->getPairs('all,noletter');
        $this->view->projects    = array(0 => '') + $this->loadModel('project')->getPairsByProgram(0, 'all', true);
        $this->view->executions  = array(0 => '') + $this->loadModel('execution')->getPairs(0, 'all', 'all|multiple');
        $this->view->rooms       = array(0 => '') + $this->loadModel('meetingroom')->getPairs();

        $this->display('meeting', 'browse');
    }
}
