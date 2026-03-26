<?php

class log extends control
{
    /**
     * The construct method.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
    }

    /**
     * Log browse page.
     *
     * @access public
     * @param string $orderBy
     * @param string $objectType
     * @param string $search
     * @param string $date
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @return void
     */
    public function browse($orderBy = 'date_desc', $objectType = 'all', $search = '', $date = '', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        if($this->post->objectType || $this->post->date)
        {
            $search = $this->post->search;
            if ($this->post->objectType) $objectType = $this->post->objectType;
            if ($this->post->date) $date = $this->post->date;
            die($this->locate(inlink('browse', "orderBy=$orderBy&objectType=$objectType&search=$search&date=$date&recTotal=0&recPerPage=$recPerPage&pageID=1")));
        }

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $userPairs = $this->loadModel('user')->getRealNamePairs();
        $userPairs['guest'] = $this->lang->log->guest;

        $this->view->records    = $this->log->getActionRecords($orderBy, $objectType, $search, $date, $pager, false);
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->objectType = $objectType;
        $this->view->search     = $search;
        $this->view->date       = $date;
        $this->view->userPairs  = $userPairs;
        $this->view->logTitle   = $this->lang->log->name;
        $this->display();
    }

    /**
     * Page for browsing admin log.
     *
     * @access public
     * @param string $orderBy
     * @param string $objectType
     * @param string $search
     * @param string $date
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     * @return void
     */
    public function admin($orderBy = 'date_desc', $objectType = 'all', $search = '', $date = '', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        if($this->post->objectType || $this->post->date)
        {
            $search = $this->post->search;
            if ($this->post->objectType) $objectType = $this->post->objectType;
            if ($this->post->date) $date = $this->post->date;
            die($this->locate(inlink('admin', "orderBy=$orderBy&objectType=$objectType&search=$search&date=$date&recTotal=0&recPerPage=$recPerPage&pageID=1")));
        }

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->records    = $this->log->getActionRecords($orderBy, $objectType, $search, $date, $pager, true);
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->objectType = $objectType;
        $this->view->search     = $search;
        $this->view->date       = $date;
        $this->view->logTitle   = $this->lang->log->admin;
        $this->display('log', 'browse');
    }
}
