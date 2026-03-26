<?php
class flowAction extends actionModel
{
    /**
     * Load custom lang.
     *
     * @access public
     * @return void
     */
    public function loadCustomLang()
    {
        $flows       = $this->loadModel('workflow', 'flow')->getList('browse', '', $status = '');   // Must a empty string to the parameter $status.
        $flowActions = $this->loadModel('workflowaction', 'flow')->getGroupList();

        foreach($flows as $flow)
        {
            if(!isset($this->lang->action->label->{$flow->module}))     $this->lang->action->label->{$flow->module}     = "$flow->name|$flow->module|view|id=%s";
            if(!isset($this->lang->action->objectTypes[$flow->module])) $this->lang->action->objectTypes[$flow->module] = $flow->name;

            if(empty($flow->buildin))
            {
                $this->config->objectTables[$flow->module]             = $flow->table;
                $this->config->action->objectNameFields[$flow->module] = 'id';
                $this->config->action->customFlows[$flow->module]      = $flow;
            }

            if(!isset($this->lang->{$flow->module}))         $this->lang->{$flow->module} = new stdclass();
            if(!isset($this->lang->{$flow->module}->action)) $this->lang->{$flow->module}->action = new stdclass();

            $actions = zget($flowActions, $flow->module, array());
            foreach($actions as $action)
            {
                if(!empty($action->buildin)) continue;

                $this->lang->{$action->module}->action->{$action->action} = sprintf($this->lang->action->desc->workflowaction, $action->name);
                $this->lang->action->label->{$action->action}             = $action->name;
                $this->lang->action->search->label[$action->action]       = $action->name;

                $actionType = $action->module . strtolower($action->action);
                $this->lang->action->label->{$actionType} = $action->name;
            }
        }
    }

    /**
     * Get deleted objects.
     *
     * @param  string $objectType
     * @param  string $type       all|hidden
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTrashes($objectType, $type, $orderBy, $pager = null)
    {
        $trashes = parent::getTrashes($objectType, $type, $orderBy, $pager);

        foreach($trashes as $trash)
        {
            if(!isset($this->config->action->customFlows[$trash->objectType])) continue;

            $flow = $this->config->action->customFlows[$trash->objectType];
            $trash->objectName = $flow->name . $trash->objectName;
        }

        return $trashes;
    }

    /**
     * Transform the actions for display.
     *
     * @param  array  $actions
     * @access public
     * @return array
     */
    public function transformActions($actions)
    {
        $actions = parent::transformActions($actions);

        foreach($actions as $action)
        {
            $flow = new stdclass();
            if(isset($this->config->action->customFlows[$action->objectType]))
            {
                $flow = $this->config->action->customFlows[$action->objectType];
                $action->objectName = $flow->name . $action->objectName;

                $actionType = $action->objectType . strtolower($action->action);
                if(isset($this->lang->action->label->{$actionType})) $action->actionLabel = $this->lang->action->label->{$actionType};
            }

            $actionType = strtolower($action->action);
            $objectType = strtolower($action->objectType);

            if(!empty($flow->parent))
            {
                $parentID   = (int)$action->extra;
                $parentID   = (!empty($parentID) || empty($flow->table)) ? $parentID : $this->dao->select('parent')->from($flow->table)->where('id')->eq($action->objectID)->fetch('parent');
                $parentFlow = isset($this->config->action->customFlows[$flow->parent]) ? $this->config->action->customFlows[$flow->parent] : '';
                if(!empty($parentID)) $action->objectLink = helper::createLink($flow->parent, 'view', "id={$parentID}");
                if(!empty($parentFlow) && !empty($parentFlow->app)) $action->objectLink .= "#app={$parentFlow->app}";
            }
            if(!empty($flow->app))
            {
                $flow->app == in_array($flow->app, array('scrum', 'waterfall')) ? 'project' : $flow->app;
                $action->objectLink .= "#app={$flow->app}";
            }
            if($objectType != 'workflowfield' && $objectType != 'workflowaction') continue;

            if(isset($this->lang->action->label->$objectType))
            {
                $objectLabel = $this->lang->action->label->$objectType;
                if(is_array($objectLabel))
                {
                    if(isset($objectLabel['common']))    $action->objectLabel = $objectLabel['common'];
                    if(isset($objectLabel[$actionType])) $action->objectLabel = $objectLabel[$actionType];
                }
                else
                {
                    $action->objectLabel = $objectLabel;
                }
            }

            $data = $this->loadModel($action->objectType, 'flow')->getByID($action->objectID);

            if(!$data) continue;

            /* Other actions, create a link. */
            if(strpos($action->objectLabel, '|') !== false)
            {
                list($objectLabel, $moduleName, $methodName, $vars) = explode('|', $action->objectLabel);
                if($action->objectType == 'workflowrule') $vars = empty($vars) ? '' : sprintf($vars, $action->objectID);
                $vars = empty($vars) ? '' : sprintf($vars, $data->module);
                $action->objectLink  = helper::createLink($moduleName, $methodName, $vars);
                $action->objectLabel = $objectLabel;
            }
            else
            {
                $action->objectLink = '';
            }
        }

        return $actions;
    }
}
