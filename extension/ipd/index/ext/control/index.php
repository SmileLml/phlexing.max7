<?php
helper::importControl('index');
class myindex extends index
{
    /**
     * The index page of whole zentao system.
     *
     * @param  string $open
     * @access public
     * @return void
     */
    public function index($open = '')
    {
        /* 把工作流自定义的流程动作页面加入 oldPages 配置中。*/
        $actions = $this->dao->select('module, action')->from(TABLE_WORKFLOWACTION)
            ->where('status')->eq('enable')
            ->andWhere('action')->in('link,report')
            ->andWhere('role')->ne('buildin')
            ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
            ->fetchAll();
        foreach($actions as $action) $this->config->index->oldPages[] = $action->module . '-' . strtolower($action->action);

        return parent::index($open);
    }
}
