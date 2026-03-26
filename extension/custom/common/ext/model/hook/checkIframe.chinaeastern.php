<?php
$module = $this->app->getModuleName();
$method = $this->app->getMethodName();

if($module == 'mergedata' and $method == 'merge') return true;
