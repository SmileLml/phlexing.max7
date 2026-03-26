<?php
$this->loadModel('attend');
$isLogon = $this->loadModel('user')->isLogon();
if($isLogon and empty($this->app->user->signed) and $this->attend->checkSignIn())
{
    $this->attend->signIn($this->app->user->account);

    $_SESSION['user']->signed = true;
    $this->app->user->signed  = true;
}
if($isLogon and (!isset($this->app->user->mustSignOut) or $this->app->user->mustSignOut != $this->config->attend->mustSignOut))
{
    $_SESSION['user']->mustSignOut = $this->config->attend->mustSignOut;
    $this->app->user->mustSignOut  = $this->config->attend->mustSignOut;
}
?>
