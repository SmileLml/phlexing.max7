<?php $bodyClass = 'with-nav-bottom';?>
<?php include '../../common/view/m.header.html.php';?>
<?php if(empty($executions)):?>
<div class="text-center">
  <p><span class="text-muted"><?php echo $lang->execution->noExecution;?></span></p>
</div>
<?php else:?>
<?php echo $this->fetch('block', 'dashboard', 'module=execution');?>
<?php endif;?>
<?php include '../../common/view/m.footer.html.php';?>
