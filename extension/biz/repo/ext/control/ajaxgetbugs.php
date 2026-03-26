<?php
helper::importControl('repo');
class myRepo extends repo
{
    /**
     * Show review.
     *
     * @param  int    $repoID
     * @param  string $bugList
     * @parma  int    $currentBug
     * @access public
     * @return void
     */
    public function ajaxGetBugs($repoID, $bugList, $currentBug = 0)
    {
        $this->loadModel('bug');
        $this->loadModel('file');
        $bugIDList = explode(',', $bugList);
        if(!$currentBug && $bugIDList) $currentBug = $bugIDList[count($bugIDList) - 1];

        $modules  = $this->loadModel('tree')->getAllModulePairs('bug');
        $bugs     = $this->repo->getBugsByRepo($repoID, 'all', 0, $bugIDList);
        $comments = $this->repo->getComments($bugIDList);
        $accounts = array();

        foreach($bugs as $bug)
        {
            $bug->files      = array();
            $bug->actions    = array();
            $bug->toCases    = array();
            $bug->moduleName = zget($modules, $bug->module, '');
            $bug = $this->file->replaceImgURL($bug, 'steps');

            $accounts[] = $bug->openedBy;
        }

        $this->view->repoID     = $repoID;
        $this->view->bugs       = $bugs;
        $this->view->bugIDList  = $bugIDList;
        $this->view->comments   = $comments;
        $this->view->currentBug = $currentBug;
        $this->view->users      = $this->loadModel('user')->getListByAccounts($accounts, 'account');
        $this->view->commentUrl = $this->repo->createLink('addComment');
        $this->display();
    }
}
