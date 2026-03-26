<?php
class myWorkflowgroup extends workflowgroup
{
    /**
     * 项目流程模版交付物配置。
     * Set deliverable.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function deliverable($groupID)
    {
        if(!empty($_POST))
        {
            $formData = form::batchData($this->config->workflowgroup->form->deliverable)->get();
            $deliverableConfig = array();
            foreach($formData as $form)
            {
                foreach($form->deliverable as $key => $deliverableGroup)
                {
                    if(empty($deliverableGroup)) continue;
                    foreach($deliverableGroup as $index => $deliverable)
                    {
                        if($deliverable) $deliverableConfig[$form->key][$key][] = array('deliverable' => $deliverable, 'required' => !empty($form->required[$key][$index]) ? true : false);
                    }
                }
            }
            $oldGroup = $this->workflowgroup->fetchByID($groupID);

            $workflowGroup = new stdclass();
            $workflowGroup->deliverable = json_encode($deliverableConfig);
            $this->dao->update(TABLE_WORKFLOWGROUP)->data($workflowGroup)->where('id')->eq($groupID)->exec();

            $changes = common::createChanges($oldGroup, $workflowGroup);
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('workflowgroup', $groupID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->sendSuccess(array('load' => true));
        }

        $deliverables    = array();
        $deliverableList = $this->dao->select('id,name,model')->from(TABLE_DELIVERABLE)->where('deleted')->eq('0')->fetchAll('id');
        foreach($deliverableList as $data) $deliverables[$data->id] = array('text' => $data->name, 'value' => $data->id);

        $workflowGroup    = $this->dao->select('name,type,deliverable,projectModel,projectType')->from(TABLE_WORKFLOWGROUP)->where('id')->eq($groupID)->fetch();
        $groupDeliverable = !empty($workflowGroup->deliverable) ? json_decode($workflowGroup->deliverable, true) : array();
        $modelList        = $this->loadModel('deliverable')->buildModelList('all', false);
        $deliverable      = array();
        foreach($modelList as $model => $label)
        {
            if(strpos($model, "{$workflowGroup->projectType}_{$workflowGroup->projectModel}") !== false)
            {
                $deliverableItem = array('key' => $model, 'object' => $label);
                /* 过滤掉已被删除的交付物。 */
                if(!empty($groupDeliverable[$model]))
                {
                    foreach($groupDeliverable[$model] as $method => $configs)
                    {
                        foreach($configs as $index => $config)
                        {
                            if(!empty($deliverableList[$config['deliverable']])) $deliverableItem[$method][$index] = $config;
                        }
                    }
                }
                $deliverable[] = $deliverableItem;
            }
        }

        $this->lang->workflow->menu->flowgroup['subMenu']->{$workflowGroup->type}['alias'] = 'deliverable';

        $this->view->title         = $workflowGroup->name . '-' . $this->lang->workflowgroup->deliverable;
        $this->view->deliverable   = $deliverable;
        $this->view->deliverables  = $deliverables;
        $this->view->workflowGroup = $workflowGroup;
        $this->display();
    }
}
