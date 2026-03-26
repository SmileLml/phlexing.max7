<?php
$index           = 1;
$actionMethod    = ($action->module == 'product' && in_array($action->method, array('requirement', 'epic'))) ? 'browse' : $action->method;
$disabledFields  = in_array($actionMethod, $config->workflowaction->defaultActions) ? zget($config->workflowlayout->disabledFields, $actionMethod, '') : $config->workflowlayout->disabledFields['custom'];
$controlList     = array('select', 'multi-select', 'checkbox', 'radio', 'date', 'datetime');
$dateControlList = array('date', 'datetime');
$requiredFields  = zget($config->workflowlayout->default->required, $action->action, array());
if($flow->approval == 'enabled') $requiredFields = array_merge($requiredFields, zget($config->workflowlayout->approval->required, $action->action, array()));
?>

<table class='table table-bordered table-fixed table-origin'>
  <?php /* Begin foreach of fields. */ ?>
  <?php foreach($fields as $key => $field):?>
  <?php
  if($mode == 'view' && !$field->show) continue;
  if(strpos(",{$disabledFields},", ",{$key},") !== false) continue;
  if(isset($field->hasAction) && !$field->hasAction) $field->defaultValue = $field->default;
  $required = in_array($key, $requiredFields);
  $fixed    = $required ? 'required' : 'enabled';
  $show     = $field->show == '1';
  $subTable = isset($subTables[$key]);
  $disabled = $mode == 'edit' ? '' : "disabled='disabled'";
  $readonly = '';
  if($subTable) $disabled = "disabled='disabled'";
  if($mode == 'edit' && !empty($flow->belong) && $field->field == $flow->belong && ($action->action == 'create' || $action->action == 'batchcreate'))
  {
      $required = true;
      $disabled = "disabled='disabled'";
  }
  ?>

  <tr class='<?php echo (!$show ? ' disabled' : '') . ($required ? ' required' : '') . (' fixed-' . $fixed) . ($subTable ? " module-{$key}" : '');?>' <?php echo $subTable ? "data-child={$key}" : '';?> data-fixed='<?php echo $fixed;?>' data-module='<?php echo $field->module;?>' data-field='<?php echo $field->field;?>' data-control='<?php echo $field->control;?>'>
    <?php /* Row title. */ ?>
    <td class='title'>
      <span class='title-bar text-ellipsis' title='<?php echo $field->name;?>'>
        <i class='icon-check'></i>
        <strong><?php echo $field->name;?></strong>
        <?php if($mode == 'edit'):?>
        <i class='icon icon-move'></i>
        <?php endif;?>
      </span>
      <?php /* Row Actions. */ ?>
      <?php if($required) echo "({$lang->workflowlayout->require})";?>
    </td>

    <?php /* Summary. */ ?>
    <?php if($actionMethod == 'browse'):?>
    <?php if(in_array($field->type, $config->workflowfield->numberTypes) && strpos(",{$config->workflowlayout->noTotalFields},", ",{$field->field},") === false):?>
    <td class='w-300px'>
      <div class='input-group'>
        <span class='text-muted input-group-addon'><?php echo $lang->workflowlayout->summary;?></span>
        <?php echo html::select("summary[$key][]", $lang->workflowlayout->summaryList, !empty($field->summary) ? $field->summary: 0, "class='form-control chosen' multiple $disabled");?>
      </div>
    </td>
    <?php else:?>
    <td></td>
    <?php endif;?>
    <?php endif;?>

    <?php /* Width. */ ?>
    <?php if($actionMethod != 'view'):?>
    <td class='w-140px'>
      <div class='input-group'>
        <span class='input-group-addon text-muted'><?php echo $lang->workflowlayout->width;?></span>
        <?php echo html::input("width[$key]", $field->width, "id='width{$key}' class='form-control' readonly");?>
      </div>
    </td>
    <?php endif;?>

    <?php if($actionMethod != 'browse' && $actionMethod != 'view' && !is_numeric($key)):?>
    <?php /* Layout rules. */ ?>
    <td class='w-160px'>
      <div class='input-group'>
        <span class='text-muted input-group-addon'><?php echo $lang->workflowlayout->layoutRules;?></span>
        <?php if($subTable):?>
        <?php echo html::select("layoutRules[$key][]", $rules, '', "class='form-control chosen' multiple='multiple' disabled");?>
        <?php else:?>
        <?php echo html::select("layoutRules[$key][]", $rules, $field->layoutRules, "class='form-control chosen' multiple='multiple' $disabled");?>
        <?php endif;?>
      </div>
    </td>

    <?php if($field->control != 'file'):?>
    <?php /* Default value. */ ?>
    <td class='w-240px'>
      <div class='input-group'>
        <span class='text-muted input-group-addon'><?php echo $lang->workflowlayout->defaultValue;?></span>
        <?php
        $data = "data-module='{$flow->module}' data-field='{$field->field}'";
        if($field->control == 'multi-select' or $field->control == 'checkbox')
        {
            echo html::select("defaultValue[$key][]", $field->options, $field->defaultValue, "id='defaultValue{$key}' class='form-control picker-select' multiple $data $disabled");
        }
        else
        {
            echo html::select("defaultValue[$key]", array('' => '') + $field->options, $field->defaultValue, "id='defaultValue{$key}' class='form-control picker-select' $data $disabled");
        }
        echo "<span class='input-group-addon fix-border'></span>";
        if(in_array($field->control, $dateControlList))
        {
            $class = 'form-' . $field->control;
            echo html::input("defaultValue[$key]", ($field->defaultValue && $field->defaultValue != 'currentTime') ? $field->defaultValue : '', "id='defaultValue{$key}' class='form-control $class' $disabled");
        }
        else
        {
            echo html::input("defaultValue[$key]", (isset($field->defaultValue) && strpos(',currentUser,currentDept,currentTime,', ",$field->defaultValue,") === false) ? $field->defaultValue : '', "id='defaultValue{$key}' class='form-control' disabled='disabled'");
        }
        $checked = '';
        if(!in_array($field->control, $controlList)) $checked .= "checked='checked'";
        if(in_array($field->control, $dateControlList) && !empty($field->defaultValue) && $field->defaultValue != 'currentTime') $checked .= "checked='checked'";
        if(!in_array($field->control, $dateControlList)) $checked .= "disabled='disabled'";
        ?>
        <span class='input-group-addon'>
          <div class='checkbox-primary'>
            <input type='checkbox' name="custom[<?php echo $key;?>]" value='1' <?php echo "$checked $disabled";?>/>
            <label><?php echo $lang->workflowlayout->custom;?></label>
          </div>
        </span>
      </div>
    </td>
    <?php else:?>
    <td class='w-240px'>
      <div class='input-group'>
        <span class='text-muted input-group-addon'><?php echo $lang->workflowlayout->defaultValue;?></span>
        <?php echo html::input("defaultValue[$key]", '', "id='defaultValue{$key}' class='form-control' disabled='disabled'");?>
        <span class='input-group-addon'>
          <div class='checkbox-primary'>
            <input type='checkbox' name="custom[<?php echo $key;?>]" value='1' disabled='disabled'/>
            <label><?php echo $lang->workflowlayout->custom;?></label>
          </div>
        </span>
      </div>
    </td>
    <?php endif;?>
    <?php endif;?>

    <?php if($actionMethod == 'browse' or ($action->type == 'batch' && $action->batchMode == 'different')):?>
    <?php /* Display in mobile device. */ ?>
    <td class='w-160px'>
      <div class='input-group'>
        <span class='text-muted input-group-addon'><?php echo $lang->workflowlayout->mobileShow;?></span>
        <?php echo html::select("mobileShow[$key]", $lang->workflowlayout->mobileList, !empty($field->mobileShow) ? $field->mobileShow : 0, "class='form-control' $disabled");?>
      </div>
    </td>
    <?php endif;?>

    <?php /* Position. */ ?>
    <?php if($actionMethod == 'view' or $actionMethod == 'browse' or $action->layout == 'side'):?>
    <?php $width = $actionMethod == 'view' ? 'w-200px' : 'w-140px';?>
    <td class="<?php echo $width;?>">
      <div class='input-group'>
        <span class='text-muted input-group-addon'>
          <?php if($actionMethod == 'view' and $index == 1):?>
          <a data-toggle='tooltip' class='position-tips' title='<?php echo $lang->workflowlayout->tips->position;?>'><i class='icon-help-circle icon-md'></i></a>
          <?php endif;?>
          <?php echo $actionMethod == 'browse' ? $lang->workflowlayout->alignment : $lang->workflowlayout->position;?>
        </span>
        <?php echo html::select("position[$key]", $positionList, !empty($field->position) ? $field->position : zget($defaultPositions, $field->field, ''), "class='form-control' $disabled");?>
      </div>
    </td>
    <?php endif;?>

    <?php /* Readonly. */ ?>
    <?php if($action->type == 'single' && !in_array($actionMethod, $config->workflowaction->readonlyActions) && $field->field != 'actions' && $actionMethod != 'browse'):?>
    <td class='w-80px'>
      <?php $checked = $field->readonly ? "checked='checked'" : '';?>
      <?php $readonlyDisabled = ($disabled or $field->control == 'file') ? "disabled='disabled'" : '';?>
      <div class='checkbox-primary'>
        <input type='checkbox' name="readonly[<?php echo $key;?>]" value='1' <?php echo "$checked $readonlyDisabled";?>/>
        <label><?php echo $lang->workflowlayout->readonly;?></label>
      </div>
    </td>
    <?php endif;?>

    <?php /* Display or not. */ ?>
    <td class='w-100px text-center'>
      <?php if($mode == 'edit'):?>
      <button type='button' class='btn btn-link show-hide' <?php echo $disabled;?>>
        <span class='label-show'><?php echo $lang->workflowlayout->show;?></span>
        <span class='text-muted'>/</span>
        <span class='label-hide'><?php echo $lang->workflowlayout->hide;?></span>
      </button>
      <?php else:?>
      <?php echo $show ? $lang->workflowlayout->show : $lang->workflowlayout->hide;?>
      <?php endif;?>
      <?php echo html::hidden("show[$key]",  $show ? '1' : '0');?>
    </td>

  </tr>
  <?php $index++;?>
  <?php endforeach;?>
  <?php /* End foreach of fields. */ ?>
</table>
