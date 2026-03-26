<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->dashboard->create;?></h2>
    </div>
    <form method='post' target='hiddenwin' id='dataform' class="form-ajax">
      <table class='table table-form'>
        <tr>
          <th class='w-100px'><?php echo $lang->dashboard->name;?></th>
          <td>
            <?php echo html::input('name', '', "class='form-control'");?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th class='w-100px'><?php echo $lang->dashboard->module;?></th>
          <td>
            <?php echo html::select('module', $modulePairs, '', "class='form-control chosen'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->dashboard->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', '', "rows='7' class='form-control'");?></td>
        </tr>
        <tr>
          <td class='form-actions text-center' colspan='3'>
            <?php echo html::submitButton() . html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
