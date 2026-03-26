<?php
helper::importControl('instance');
class myInstance extends instance
{
    /**
     * @param int $instanceID
     * @param string $type
     */
    public function ajaxUninstall($instanceID, $type = '')
    {
        if($type == 'store')
        {
            $instance = $this->instance->getByID($instanceID);
            if(!$instance) return $this->send(array('result' => 'success', 'message' => $this->lang->instance->notices['success'], 'load' => $this->createLink('space', 'browse')));
            if($instance->source == 'system') return false;
        }

        parent::ajaxUninstall($instanceID, $type);
    }
}
