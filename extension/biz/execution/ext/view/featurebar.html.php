<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(isset($ganttType)):?>
    <?php echo html::a('javascript:updateCriticalPath()', $lang->execution->gantt->showCriticalPath, '', "class='btn btn-link' id='criticalPath'");?>
    <?php echo html::a('###', "<i class='icon icon-fullscreen'></i> " . $lang->execution->gantt->fullScreen, '', "class='btn btn-link' id='fullScreenBtn'");?>
    <div class='btn btn-link' id='ganttPris'>
      <strong><?php echo $lang->task->pri . " : "?></strong>
      <?php foreach($lang->execution->gantt->progressColor as $pri => $color):?>
      <?php if($pri <= 4):?>
      <span style="background:<?php echo $color?>"><?php echo $pri;?></span> &nbsp;
      <?php endif;?>
      <?php endforeach;?>
    </div>
    <?php else:?>
    <?php echo html::a($this->createLink('execution', 'gantt', "executionID=$executionID"), "<i class='icon icon-back icon-sm'></i> " . $lang->goback, '', "class='btn btn-secondary'");?>
    <?php endif;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(isset($ganttType)):?>
    <?php if(common::hasPriv('execution', 'ganttsetting')) echo html::a($this->createLink('execution', 'ganttsetting', "executionID=$executionID", '', true), "<i class='icon icon-cog-outline muted'></i> " . $lang->execution->ganttSetting, '', "class='btn btn-link iframe' data-width='45%'");?>
    <?php if(common::hasPriv('execution', 'relation') && $config->vision != 'lite') echo html::a($this->createLink('execution', 'relation', "executionID=$executionID"), "<i class='icon icon-list-alt muted'></i> " . $lang->execution->maintainRelation, '', "class='btn btn-link'");?>
    <?php endif;?>
    <?php if($this->app->rawMethod != 'relation' and $this->app->rawMethod != 'maintainrelation'):?>
    <div class="btn-group">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export ?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu" id='exportActionMenu'>
        <li><a href='javascript:exportGantt()'><?php echo $lang->execution->gantt->exportImg;?></a></li>
        <li><a href='javascript:exportGantt("pdf")'><?php echo $lang->execution->gantt->exportPDF;?></a></li>
      </ul>
    </div>
    <?php else:?>
    <?php
      if(common::hasPriv('execution', 'maintainRelation')) echo html::a($this->createLink('execution', 'maintainRelation', "executionID=$executionID"), "<i class='icon icon-plus'></i> " . $lang->execution->gantt->editRelationOfTasks, '', "class='btn btn-secondary'");
    ?>
    <?php endif;?>
    <?php
    $checkObject = new stdclass();
    $checkObject->execution = $executionID;
    $misc = common::hasPriv('task', 'create', $checkObject) ? "class='btn btn-primary iframe' data-size='lg' data-toggle='modal'" : "class='btn btn-primary disabled'";
    $link = common::hasPriv('task', 'create', $checkObject) ?  $this->createLink('task', 'create', "execution=$executionID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : ''), '', true) : '#';
    echo html::a($link, "<i class='icon icon-plus'></i> " . $lang->task->create, '', $misc);
    ?>
  </div>
</div>
