<?php
class instance extends control
{
    /**
     * 通过 appID 和 应用ID 获取自定义配置信息。
     * Get custom fields by appID and instanceID.
     *
     * @param  int    $appID
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function ajaxGetCustoms($appID, $instanceID = 0)
    {
        $customFields = array();
        if($instanceID)
        {
            $instance = $this->instance->getByID($instanceID);
            if($instance) $customFields = $this->loadModel('cne')->getCustomFields($instance);
        }
        else
        {
            $cloudApp = $this->loadModel('store')->getAppInfo($appID);
            if($cloudApp) $customFields = zget($cloudApp, 'custom_settings', array());
        }

        foreach($customFields as $field) $this->lang->instance->{$field->name} = common::checkNotCN() ? $field->name : $field->label;

        $this->session->set("instanceFields{$appID}", $customFields);

        $this->view->customFields = $customFields;
        $this->view->instanceID   = $instanceID;
        $this->display();
    }
}
