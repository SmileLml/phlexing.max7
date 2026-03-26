<?php
class api extends control
{
    /**
     * Get all users of company
     * 
     * @access public
     * @return object
     */
    public function mobileGetUsers()
    {
        if($this->get->lang and $this->get->lang != $this->app->getClientLang())
        {
            $this->app->setClientLang($this->get->lang);
            $this->app->loadLang('api');
        }

        if(!$this->loadModel('user')->isLogon()) die(json_encode(array('status' => 'failed', 'reason' => $this->lang->api->failLogin)));

        $users = $this->loadModel('user')->getList();
        $data  = array();
        foreach ($users as $user)
        {
            $userData = array();
            $userData['id'] = (int) $user->id;
            if(!empty($user->realname)) $userData['realname'] = $user->realname;
            $userData['account'] = $user->account;
            if(!empty($user->role)) $userData['role']     = $user->role;
            if(!empty($user->email)) $userData['email']   = $user->email;
            if(!empty($user->phone)) $userData['phone']   = $user->phone;
            if(!empty($user->gender)) $userData['gender'] = $user->gender;

            $userData['groups'] = join(',', array_keys($this->loadModel('group')->getByAccount($user->account)));

            $data[] = $userData;
        }
        
        die($this->api->compression($data));
    }
}
