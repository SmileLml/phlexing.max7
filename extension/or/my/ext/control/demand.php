<?php
class myMy extends my
{
    /**
     * 需求池需求列表 。
     * My demands.
     *
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function demand($type = 'assignedTo', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        if($this->app->viewType != 'json') $this->session->set('demandList', $this->app->getURI(true), 'my');

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);
        $queryID = $type == 'bysearch' ? (int)$param : 0;

        $this->loadModel('demand');
        if($type == 'assignedBy')
        {
            $demands = $this->my->getDemandAssignedByMe($this->app->user->account, $pager, $sort);
        }
        elseif($type == 'bysearch')
        {
            $demands = $this->my->getDemandsBySearch($this->app->user->account, $queryID, $sort, $pager);
        }
        else
        {
            $demands = $this->demand->getUserDemands($this->app->user->account, $type, $sort, $pager);
        }

         /* Build the search form. */
        $currentMethod = $this->app->rawMethod;
        $actionURL     = $this->createLink('my', $currentMethod, "mode=demand&type=bysearch&param=myQueryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
        $this->my->buildDemandSearchForm($queryID, $actionURL);

        $this->myZen->showWorkCount($recTotal, $recPerPage, $pageID);

        /* Assign. */
        $this->view->title    = $this->lang->my->common . $this->lang->hyphen . $this->lang->my->demand;
        $this->view->demands  = $demands;
        $this->view->users    = $this->user->getPairs('noletter');
        $this->view->type     = $type;
        $this->view->param    = $param;
        $this->view->mode     = 'demand';
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;
        $this->display();
    }
}
