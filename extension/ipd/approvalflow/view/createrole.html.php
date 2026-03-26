<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->approvalflow->role->create;?></h2>
  </div>
  <form method='post' class='form-ajax' id='dataform'>
    <table align='center' class='table table-form'>
      <tr>
        <th><?php echo $lang->approvalflow->role->name;?></th>
        <td class='required'><?php echo html::input('name', '', "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->approvalflow->role->code;?></th>
        <td><?php echo html::input('code', '', "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->approvalflow->role->member;?></th>
        <td colspan='2'><?php echo html::select('users[]', $users, '', "class='form-control chosen' multiple");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->approvalflow->role->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', '', "rows=10 class=form-control");?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
