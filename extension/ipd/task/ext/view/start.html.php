<?php $message = $this->task->checkDepend($task->id, 'begin');?>
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
chdir($app->getModuleRoot() . 'task/view/');
include './start.html.php';
chdir($oldDir);
?>
<?php endif;?>
