<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 创建项目引导。
     * Project create guide.
     *
     * @param  int    $programID
     * @param  string $from
     * @param  int    $productID
     * @param  int    $branchID
     * @param  int    $charterID
     * @access public
     * @return void
     */
    public function createGuide($programID = 0, $from = 'project', $productID = 0, $branchID = 0, $charterID = 0)
    {
        $this->view->charterID = $charterID;
        $this->view->templates = $this->dao->select('id,name,model,workflowGroup,`desc`')->from(TABLE_PROJECT)->where('isTpl')->eq('1')->andWhere('type')->eq('project')->fetchAll('id');
        return parent::createGuide($programID, $from, $productID, $branchID);
    }
}
