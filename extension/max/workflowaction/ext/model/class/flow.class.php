<?php
class flowWorkflowaction extends workflowactionModel
{
    /**
     * Get fields.
     *
     * @param  string  $module
     * @param  string  $action
     * @param  bool    $getRealOptions
     * @param  array   $datas
     * @param  int     $ui
     * @param  int     $groupID
     * @access public
     * @return array
     */
    public function getFields($module, $action, $getRealOptions = true, $datas = array(), $ui = 0, $groupID = null)
    {
        $fields = parent::getFields($module, $action, $getRealOptions, $datas, $ui, $groupID);

        if($this->config->moreLinks)
        {
            $action = $this->getByModuleAndAction($module, $action);

            /* Get datasource id list. */
            $datasourceIdList = array();
            $fieldIdList      = array();
            foreach($this->config->moreLinks as $fieldName => $moreLink)
            {
                if(!isset($fields[$fieldName])) continue;

                $field = $fields[$fieldName];
                $fieldIdList[$fieldName] = $field->id;
            }
            $datasourceIdList = $this->dao->select('field, options')->from(TABLE_WORKFLOWFIELD)->where('id')->in($fieldIdList)->fetchPairs('field', 'options');

            /* Get single append data. */
            $appendData = new stdclass();
            if(isset($action->type) and $action->type == 'single' and !empty($datas))
            {
                foreach($datas as $fieldName => $fieldValue)
                {
                    if(isset($this->config->moreLinks[$fieldName])) $appendData->$fieldName = $fieldValue;
                }
            }

            /* Get append data for data list. */
            if(isset($action->type) and $action->type == 'batch' and !empty($datas))
            {
                foreach($datas as $item)
                {
                    if(!is_object($item)) continue;
                    foreach($item as $fieldName => $fieldValue)
                    {
                        if(isset($this->config->moreLinks[$fieldName]))
                        {
                            if(!isset($appendData->$fieldName)) $appendData->$fieldName = '';
                            $appendData->$fieldName .= ',' . $fieldValue;
                        }
                    }
                }
            }

            /* Get options with append data. */
            $this->loadModel('workflowfield');
            foreach($this->config->moreLinks as $fieldName => $moreLink)
            {
                if(!isset($fields[$fieldName])) continue;

                $options = array();
                if(isset($datasourceIdList[$fieldName])) $options = $this->workflowfield->getOptionsByDatasource($datasourceIdList[$fieldName], zget($appendData, $fieldName, ''));

                $fields[$fieldName]->options = $options;
            }
        }

        return $fields;
    }

    /**
     * Save notice.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function saveNotice($id)
    {
        $this->post->toList = $this->post->mailto;
        return parent::saveNotice($id);
    }

    /**
     * Update
     *
     * @param  int    $id
     * @access public
     * @return array
     */
    public function update($id)
    {
        $oldAction = $this->getByID($id);
        $module    = $this->post->module;
        $this->uniqueCondition = "id!='$id' AND module='$module' AND action='$oldAction->action' AND vision='{$this->config->vision}' AND `group`='$oldAction->group'";
        return parent::update($id);
    }

    /**
     * Check if the button is clickable.
     *
     * @param  object $action
     * @param  string $methodName
     * @access public
     * @return bool
     */
    public function isClickable($action, $methodName)
    {
        if($action->status != 'enable') return false;
        $methodName = strtolower($methodName);

        if($methodName == 'browsecondition' && $action->method == 'batchoperate' && $action->buildin == '1') return false;

        return parent::isClickable($action, $methodName);
    }
}
