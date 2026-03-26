<?php
class api extends control
{
    /**
     * Get user.
     * 
     * @access public
     * @return void
     */
    public function mobileGetUser()
    {
        if($this->get->lang and $this->get->lang != $this->app->getClientLang())
        {
            $this->app->setClientLang($this->get->lang);
            $this->app->loadLang('api');
        }

        if(!$this->loadModel('user')->isLogon()) die(json_encode(array('status' => 'failed', 'reason' => $this->lang->api->failLogin)));

        $user = $this->user->getById($this->app->user->account);
        if(empty($user)) die(json_encode(array('status' => 'failed', 'reason' => $this->lang->api->failNoFind)));
        if($user->deleted != 0) die(json_encode(array('status' => 'failed', 'reason' => $this->lang->api->failDeleted)));

        $user->groups = join(',', array_keys($this->loadModel('group')->getByAccount($this->app->user->account)));

        unset($user->password);
        die($this->api->compression($user));
    }
}
