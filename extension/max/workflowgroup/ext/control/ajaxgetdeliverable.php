<?php
class myWorkflowgroup extends workflowgroup
{
    /**
     * 动态获取交付物下拉列表。
     * Ajax get deliverable list.
     *
     * @param  string $model
     * @param  string $method
     * @param  string $current
     * @access public
     * @return void
     * @param string $code
     */
    public function ajaxGetDeliverable($model, $code, $current)
    {
        $method = '';
        if($code == 'whenClosed')  $method = 'close';
        if($code == 'whenCreated') $method = 'create';

        $module = 'execution';
        if(in_array($model, array('project_scrum', 'product_scrum', 'product_waterfall', 'project_waterfall'))) $module = 'project';

        $formData = form::batchData($this->config->workflowgroup->form->deliverable)->get();

        $disabled = array();
        foreach($formData as $data)
        {
            if($data->key !== $model) continue;
            $disabled = array_merge($disabled, !empty($data->deliverable['whenCreated']) ? $data->deliverable['whenCreated'] : array());
            $disabled = array_merge($disabled, !empty($data->deliverable['whenClosed'])  ? $data->deliverable['whenClosed']  : array());
        }

        $deliverables    = array();
        $deliverableList = $this->dao->select('id,name,model')->from(TABLE_DELIVERABLE)->where('module')->eq($module)->andWhere('method')->eq($method)->andWhere('deleted')->eq('0')->fetchAll();
        foreach($deliverableList as $deliverable)
        {
            if(strpos(",{$deliverable->model},", ",$model,") !== false) $deliverables[] = array('text' => $deliverable->name, 'value' => $deliverable->id, 'disabled' => $deliverable->id != $current && in_array($deliverable->id, $disabled));
        }
        return $this->send($deliverables);
    }
}
