<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php
$itemRow = <<<EOT
<tr class="text-center">
  <td>
    <input type="text" name="values[]" id="values[]" value="" class="form-control" autocomplete="off">
    <input type="hidden" name="systems[]" value="0">
  </td>
  <td>
    <input type="text" name="keys[]" id="keys[]" value="" class="form-control" autocomplete="off">
  </td>
  <td class="c-actions">
    <a href="javascript:void(0)" onclick="addClassify(this)" class="btn btn-link"><i class="icon-plus"></i></a>
    <a href="javascript:void(0)" onclick="delClassify(this)" class="btn btn-link"><i class="icon-close"></i></a>
  </td>
</tr>
EOT
;
?>
<?php js::set('itemRow', $itemRow)?>
<div id="mainContent" class="main-row">
  <div class='side-col' id='sidebar'>
    <div class='cell'><?php include '../../process/view/menu.html.php';?></div>
  </div>
  <div class="main-content">
    <form class="load-indicator main-form form-ajax" method="post">
      <div class="main-header">
        <div class="heading">
          <strong><?php echo $lang->classify->classifyAdd;?></strong>
        </div>
      </div>
      <table class="table table-form active-disabled table-condensed mw-600px">
        <tbody>
        <tr class="text-left">
          <td class="w-200px"><strong><?php echo $lang->classify->classifyName;?></strong></td>
          <td class="w-120px"><strong><?php echo $lang->classify->classifyLable;?></strong></td>
          <th class="w-90px"></th>
        </tr>
        <?php foreach ($processLang as $index => $process):?>
          <tr class="text-center">
            <td>
              <input type="text" name="values[]" id="values[]" value="<?php echo $process->value;?>" <?php if($process->system) echo 'readonly=""';?> class="form-control" autocomplete="off">
              <input type="hidden" name="systems[]" value="<?php echo $process->system;?>">
            </td>
            <td>
              <input type="text" name="keys[]" id="keys[]" value="<?php echo $process->key;?>" <?php if($process->system) echo 'readonly=""';?> class="form-control" autocomplete="off">
            </td>
            <td class="c-actions">
              <?php if(!$process->system):?>
              <a href="javascript:void(0)" onclick="addClassify(this)" class="btn btn-link"><i class="icon-plus"></i></a>
              <a href="javascript:void(0)" onclick="delClassify(this)" class="btn btn-link"><i class="icon-close"></i></a>
              <?php endif;?>
            </td>
          </tr>
        <?php endforeach;?>
        <tr class="text-center">
          <td>
            <input type="text" name="values[]" id="values[]" value="" class="form-control" autocomplete="off">
            <input type="hidden" name="systems[]" value="0">
          </td>
          <td>
            <input type="text" name="keys[]" id="keys[]" value="" class="form-control" autocomplete="off">
          </td>
          <td class="c-actions">
            <a href="javascript:void(0)" onclick="addClassify(this)" class="btn btn-link"><i class="icon-plus"></i></a>
            <a href="javascript:void(0)" onclick="delClassify(this)" class="btn btn-link"><i class="icon-close"></i></a>
          </td>
        </tr>
        <tr>
          <td colspan="3" class="text-center form-actions">
            <?php echo html::submitButton($lang->save, "btn btn-wide btn-primary");?>
            <?php echo html::backButton();?>
          </td>
        </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
<style>
.main-header {
  display: flex;
  height: 50px;
  align-items: center
}
</style>
