<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2>
        <span class='label label-id'><?php echo $charter->id;?></span>
        <span title='<?php echo $charter->name;?>'><?php echo $charter->name;?></span>
      </h2>
    </div>
    <form class="load-indicator main-form" method='post' target='hiddenwin' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->charter->closedReason;?></th>
            <td><?php echo html::select('closedReason', $lang->charter->closeReasonList, '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->comment;?></th>
            <td colspan='3'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
          </tr>
          <tr>
            <td class='form-actions text-center' colspan='4'><?php echo html::submitButton($lang->close);?></td>
          </tr>
        </tbody>
      </table>
    </form>
    <hr/>
    <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
