<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $nc->id;?></span>
        <small><?php echo $nc->title . $lang->arrow . $lang->nc->resolve;?></small>
      </h2>
    </div>
    <form method='post' enctype='multipart/form-data' class='form-ajax'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->nc->resolution;?></th>
          <td class='w-p35-f'><?php echo html::select('resolution', $lang->nc->resolutionList, 'fixed', "class='form-control chosen'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->nc->resolvedDate;?></th>
          <td>
            <div class='datepicker-wrapper datepicker-date'>
              <?php echo html::input('resolvedDate', helper::now(), "class='form-control form-date'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->assignedTo;?></th>
          <td><?php echo html::select('assignedTo', $users, $nc->assignedTo, "class='form-control chosen'");?></td>
        </tr>
        <?php echo html::hidden('status', 'resolved');?>
        <tr>
          <th><?php echo $lang->nc->desc;?></th>
          <td colspan='3'><?php echo html::textarea('desc', $nc->desc, "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <td class='text-center form-actions' colspan='4'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
