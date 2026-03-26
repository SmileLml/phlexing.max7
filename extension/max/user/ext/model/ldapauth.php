<?php
/**
 * @return object|bool
 * @param string $account
 * @param string $password
 * @param int $passwordStrength
 */
public function identify($account, $password, $passwordStrength = 0)
{
    /* If ionCube is not loaded, jump to loader-wizard.php. */
    $user = parent::identify($account, $password, $passwordStrength);
    if($user and !extension_loaded('ionCube Loader') and !extension_loaded('swoole_loader'))
    {
        $user->rights = $this->authorize($account);
        $user->groups = $this->getGroups($account);
        $user->admin  = strpos($this->app->company->admins, ",{$user->account},") !== false;
        $this->session->set('user', $user);

        $documentRoot = isset($_SERVER['SCRIPT_FILENAME']) ? dirname($_SERVER['SCRIPT_FILENAME']) : $_SERVER['DOCUMENT_ROOT'];
        $link         = is_file($documentRoot . '/loader-wizard.php') ? 'loader-wizard.php' : 'http://www.ioncube.com/lw/';
        die(js::locate($link, 'parent'));
    }

    return $this->loadExtension('ldapauth')->identify($account, $password, $passwordStrength, $user);
}

public function getLDAPConfig()
{
    return $this->loadExtension('ldapauth')->getLDAPConfig();
}

public function getLDAPUser($type = 'all', $queryID = 0)
{
    return $this->loadExtension('ldapauth')->getLDAPUser($type, $queryID);
}

public function importLDAP($type = 'all', $queryID = 0)
{
    return $this->loadExtension('ldapauth')->importLDAP($type, $queryID);
}

public function getUserWithoutLDAP()
{
    return $this->loadExtension('ldapauth')->getUserWithoutLDAP();
}
