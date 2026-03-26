<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $nc->id;?></span>
        <small><?php echo $nc->title . $lang->arrow . $lang->nc->close;?></small>
      </h2>
    </div>
    <form method='post' class='form-ajax'>
      <table class='table table-form'>
        <tr>
          <th class='w-60px'><?php echo $lang->comment;?></th>
          <td><?php echo html::textarea('comment', '', "rows='6' class='w-p98'");?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center form-actions'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
