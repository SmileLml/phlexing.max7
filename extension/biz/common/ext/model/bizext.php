<?php
public function setCompany()
{
    if(!extension_loaded('ionCube Loader')) return parent::setCompany();
    if(!file_exists($this->app->configRoot . 'license' . DS . 'zentao.txt')) return parent::setCompany();

    return $this->loadExtension('bizext')->setCompany();
}
