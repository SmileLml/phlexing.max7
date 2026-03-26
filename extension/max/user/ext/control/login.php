<?php
helper::importControl('user');
class myUser extends user
{
    /**
     * @param string $referer
     * @param string $type
     */
    public function login($referer = '', $type = 'ldap')
    {
        $ldapConfig = $this->user->getLDAPConfig();
        if(!empty($ldapConfig->turnon) && $type == 'ldap') $this->config->notMd5Pwd = true;

        return parent::login($referer);
    }
}
