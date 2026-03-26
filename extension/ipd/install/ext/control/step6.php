<?php
helper::importControl('install');
class myinstall extends install
{
    public function step6()
    {
        $this->config->version = $this->config->ipdVersion;
        return parent::step6();
    }
}
