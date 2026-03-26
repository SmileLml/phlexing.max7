<?php $message = $this->task->checkDepend($task->id, 'end');?>
<?php if($message):?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class="alert with-icon">
    <i class="icon-exclamation-sign"></i>
    <div class="content">
      <p><?php echo str_replace('\n', '<br />', $message);?></p>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
<?php else:?>
<?php
$oldDir = getcwd();
chdir($app->getModuleRoot() . 'task/view');
include './finish.html.php';
chdir($oldDir);
?>
<?php endif;?>
