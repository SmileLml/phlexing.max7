<?php
helper::importControl('instance');
class myInstance extends instance
{
    /**
     * @param int $id
     * @param string $type
     */
    public function setting($id, $type = '')
    {
        if($type != 'custom') return parent::setting($id);

        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);

        if(!empty($_POST))
        {
            $instance = $this->instance->getByID($id);
            $this->buildCustomConfig($instance->appID);
            if(empty($this->config->instance->form->custom)) $this->sendSuccess(array('load' => true));

            $this->checkCustomFields($instance->appID);
            if(dao::isError()) return $this->sendError(dao::getError());

            $settings = new stdclass();
            $settings->settings_map = array('custom' => form::data($this->config->instance->form->custom)->get());

            if($this->loadModel('cne')->updateConfig($instance, $settings))
            {
                $oldData = new stdclass();
                foreach($this->session->{"instanceFields{$instance->appID}"} as $field)
                {
                    if($field->type != 'password') $oldData->{$field->name} = zget($field, 'value', '');
                }

                $changes  = common::createChanges($oldData, $settings->settings_map['custom']);
                $actionID = $this->loadModel('action')->create('instance', $id, 'EditCustomFields');
                $this->action->logHistory($actionID, $changes);
            }
            $this->sendSuccess(array('load' => true));
        }
    }
}
