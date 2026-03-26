<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('lang', $lang->dataset);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->dataset->create;?></h2>
    </div>
    <div class="panel">
      <form method='post' target='hiddenwin' id='dataform' class="form-ajax">
        <table class='table table-form'>
          <tr>
            <th class='thWidth'><?php echo $lang->dataset->name;?></th>
            <td class='w-400px'>
              <?php echo html::input('name', '', 'class="form-control"');?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->dataset->sql;?></th>
            <td>
            <?php echo html::textarea('sql', '', "placeholder='" . $lang->dataset->sqlPlaceholder . "' class='form-control' rows=6");?>
            </td>
          </tr>
          <tr class="error hidden"><th></th><td></td></tr>
          <tr>
            <td></td>
            <td class='form-actions'>
              <button type="button" class="btn query btn-primary"><?php echo $lang->dataset->query;?></button>
              <button type="button" class="btn fieldSettings"><?php echo $lang->dataset->fieldSettings;?></button>
              <button type="button" class="btn save"><?php echo $lang->save;?></button>
            </td>
          </tr>
        </table>
      </form>
    </div>
    <div class="panel table-panel">
      <div class="panel-heading"><?php echo $lang->dataset->result?></div>
      <table class="result table table-condensed table-striped table-bordered table-fixed datatable">
      </table>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
