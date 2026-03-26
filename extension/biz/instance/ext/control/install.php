<?php
helper::importControl('instance');
class myInstance extends instance
{
    /**
     * @param int $appID
     * @param string $checkResource
     */
    public function install($appID, $checkResource = 'true')
    {
        if(!empty($_POST))
        {
            form::data($this->config->instance->form->install)->get();

            $this->buildCustomConfig($appID);
            $this->checkCustomFields($appID);
            if(dao::isError()) return $this->sendError(dao::getError());

            if(!empty($this->config->instance->form->install->custom))
            {
                $this->config->instance->form->install = array_merge($this->config->instance->form->install, $this->config->instance->form->install->custom);
            }
        }
        parent::install($appID, $checkResource);
    }
}
