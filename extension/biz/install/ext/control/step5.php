<?php
helper::importControl('install');
class myInstall extends install
{
    public function step5()
    {
        if(!empty($_POST) && !extension_loaded('ionCube Loader')) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert({'content': '{$this->lang->install->uninstallLoader}', 'size': '420px'});"));

        return parent::step5();
    }
}
