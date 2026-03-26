<?php
/**
 * @param object $data
 */
public function grantPriv($data)
{
    parent::grantPriv($data);
    if(dao::isError()) return false;
    $this->loadModel('upgrade')->importBuildinModules();
    $this->loadModel('upgrade')->addSubStatus();
    return true;
}
