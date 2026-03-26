<?php
$this->app->loadLang('process');
$this->app->loadLang('activity');
$this->app->loadLang('zoutput');
$this->app->loadLang('classify');
$moduleName = $this->app->moduleName;
$method     = $model == 'waterfall' ? 'browse' : $model . 'browse';
?>
<div class='list-group tab-menu'>
  <?php if(common::hasPriv('classify', 'browse')) echo html::a($this->createLink('classify', 'browse', "type=$model"),       $lang->classify->browse, '', "class='classifyTab " . ($moduleName == 'classify' ? 'active' : '') . "'");?>
  <?php if(common::hasPriv('process', $method))   echo html::a($this->createLink('process',  $method,  "browseType=$model"), $lang->process->list,    '', "class='processTab " . ($moduleName == 'process' ? 'active' : '') . "'");?>
  <?php if(common::hasPriv('activity', 'browse')) echo html::a($this->createLink('activity', 'browse', "model={$model}"), $lang->activity->browse, '', "class='activityTab " . ($moduleName == 'activity' ? 'active' : '') . "'");?>
  <?php if(common::hasPriv('zoutput', 'browse'))  echo html::a($this->createLink('zoutput',  'browse', "model={$model}"), $lang->zoutput->browse,  '', "class='zoutputTab "  . ($moduleName == 'zoutput'  ? 'active' : '') . "'");?>
</div>
