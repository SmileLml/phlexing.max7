<div class='settingBox'>
  <?php $flowName = !empty($flowPairs) ? zget($flowPairs, $module) : $flow->name;?>
  <?php echo html::checkbox($mode, array($module => $lang->workflowfield->$mode), !empty($checkedFields) ? $module : '', "data-name='$flowName'", 'block');?>
  <div class='panel fieldBox <?php if(empty($checkedFields)) echo 'hide';?> w-400px'>
    <div class='checkbox check-all' title='<?php echo $lang->selectAll;?>'>
      <label>
        <input type='checkbox' name='checkAll[<?php echo $module;?>]' id='checkAll<?php echo $module;?>' <?php if(!empty($checkedFields)) echo 'checked';?> value='1'>
        <?php echo $lang->workflowfield->fieldName;?>
      </label>
    </div>
    <div class='sortable'>
      <?php echo html::checkbox("fields[$module]", $fields, array_keys($checkedFields), '', 'block');?>
    </div>
  </div>
</div>
<?php echo html::hidden('modules[]', $module);?>
