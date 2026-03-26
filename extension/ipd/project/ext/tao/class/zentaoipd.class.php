<?php
class zentaoipdProjectTao extends projectTao
{
    /**
     * 根据项目模式设置菜单。
     * Set menu by project model.
     *
     * @param  string    $projectModel
     * @access protected
     * @return bool
     */
    protected function setMenuByModel($projectModel)
    {
        $result = parent::setMenuByModel($projectModel);

        if($projectModel == 'ipd') unset($this->lang->project->menu->other['dropMenu']->pssp, $this->lang->project->menu->other['dropMenu']->auditplan, $this->lang->project->menu->review['subMenu']->issue);

        return $result;
    }
}

