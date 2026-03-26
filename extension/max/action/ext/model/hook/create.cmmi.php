<?php
$this->loadModel('measurement');
$module = $this->app->getModuleName();
$method = $this->app->getMethodName();
if(isset($this->lang->measurement->actions["$module.$method"]))
{
}
