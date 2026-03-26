<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 项目模板列表页面。
     * Project template list.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function template($orderBy = 'id_asc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $this->lang->project->common = $this->lang->project->template;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $templates = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->andWhere('isTpl')->eq('1')
            ->andWhere('type')->eq('project')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $this->view->title          = $this->lang->project->templateList;
        $this->view->templates      = $templates;
        $this->view->orderBy        = $orderBy;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->workflowGroups = $this->loadModel('workflowGroup')->getAllPairs();
        $this->view->pager          = $pager;
        $this->display();
    }
}
