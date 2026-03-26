<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('demandStatus', $demand->status);?>
<?php js::set('page', 'edit')?>
<div id="mainContent" class="main-content fade">
  <?php 
  if(strpos('draft,changing', $demand->status) !== false)
  {
    include './editdraft.html.php';
  }
  else
  {
    include './editnormal.html.php';
  }
  ?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
