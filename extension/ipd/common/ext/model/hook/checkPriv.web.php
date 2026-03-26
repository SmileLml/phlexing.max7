<?php
if($this->app->viewType == 'mhtml')
{
    $module = $this->app->getModuleName();
    $tab    = isset($this->lang->navGroup->$module) ? $this->lang->navGroup->$module : $module;

    setcookie('tab', $tab, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
    $_COOKIE['tab'] = $tab;
    $this->app->tab = $tab;
}
