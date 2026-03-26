<?php
helper::importControl('tree');
class mytree extends tree
{
    /**
     * @param int $moduleID
     * @param string $type
     * @param string $branch
     */
    public function edit($moduleID, $type, $branch = '0')
    {
        if(strpos($this->config->tree->groupTypes, ",$type,") !== false)
        {
            $this->loadModel('chart');
            $this->lang->tree->edit   = str_replace($this->lang->tree->module, $this->lang->chart->group, $this->lang->tree->edit);
            $this->lang->tree->parent = str_replace($this->lang->tree->module, $this->lang->chart->group, $this->lang->tree->parent);
            $this->lang->tree->name   = str_replace($this->lang->tree->module, $this->lang->chart->group, $this->lang->tree->name);

            $module = $this->tree->getByID($moduleID);
            $this->view->optionMenu = (!empty($module) and $module->grade == '2') ? $this->tree->getGroupPairs($module->root, 0, 1) : array(0 => '/');
        }

        return parent::edit($moduleID, $type, $branch);
    }
}
