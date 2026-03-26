<div id='subTables-list'>
  <?php $disabledFields = $config->workflowlayout->disabledFields['subTables'];?>
  <?php /* Begin foreach of sub tables. */ ?>
  <?php foreach($subTables as $childModule => $childFields):?>
  <?php
  $display = true;
  if($mode == 'view')
  {
      $display = false;
      foreach($childFields as $field)
      {
          if($field->show)
          {
              $display = true;
              break;
          }
      }
  }
  if(!$display) continue;
  ?>
  <div class='panel'>
    <table class='table table-layout child child-<?php echo $childModule;?>' data-module="<?php echo $childModule;?>">
      <?php /* Block Title */ ?>
      <?php $blockTitle = $lang->workflow->subTable . $lang->hyphen . zget($flowPairs, str_replace('sub_', '', $childModule));?>
      <tr class='head'>
        <?php $cols = $action->method == 'view' ? 4 : 6;?>
        <td colspan="<?php echo $cols;?>">
          <i class='icon-check'></i>
          <?php if($mode == 'edit'):?>
          <span class='title'><span class='title-bar'><strong><?php echo $blockTitle;?></strong></span></span>
          <?php else:?>
          <strong><?php echo $blockTitle;?></strong>
          <?php endif;?>
        </td>
      </tr>

      <?php /* Begin foreach of childFields. */ ?>
      <?php foreach($childFields as $key => $field):?>
      <?php if($mode == 'view' && !$field->show) continue;?>
      <?php if($action->method != 'view' && strpos(",{$disabledFields},", ",{$key},") !== false) continue;?>
      <?php $required = $key == 'actions';?>
      <?php $fixed    = $required ? 'required' : 'enabled';?>
      <?php $show     = $field->show == '1';?>
      <?php $disabled = $mode == 'edit' ? '' : "disabled='disabled'";?>
      <tr class='<?php echo ($show ? '' : ' disabled');?>' data-fixed='<?php echo $fixed;?>' data-module='<?php echo $field->module;?>' data-field='<?php echo $field->field;?>' data-control='<?php echo $field->control;?>'>
        <td>
          <?php /* Row title. */ ?>
          <span class='title'>
            <span class='title-bar text-ellipsis' title='<?php echo $field->name;?>'>
              <i class='icon-check'></i>
              <strong><?php echo $field->name;?></strong>
              <?php if($mode == 'edit'):?>
              <i class='icon icon-move'> </i>
              <?php endif;?>
            </span>
          </span>
          <?php if($required):?>
          <span class='text-muted'><?php echo '(' . $lang->workflowlayout->require . ')';?></span>
          <?php endif;?>
        </td>

        <?php /* Row actions. */ ?>
        <td class='w-130px'>
          <?php /* Width. */ ?>
          <div class='input-group'>
            <span class='input-group-addon text-muted'><?php echo $lang->workflowlayout->width;?></span>
            <?php echo html::input("subTables[$childModule][width][$key]", $field->width, "id='subTables{$childModule}width{$key}' class='form-control' readonly");?>
          </div>
        </td>
        <?php if($action->method == 'view'):?>
        <td class='w-160px'>
          <?php /* Display in mobile device. */ ?>
          <div class='input-group'>
            <span class='input-group-addon text-muted'><?php echo $lang->workflowlayout->mobileShow;?></span>
            <?php echo html::select("subTables[$childModule][mobileShow][$key]", $lang->workflowlayout->mobileList, zget($field, 'mobileShow', 0), "class='form-control' $disabled");?>
          </div>
        </td>
        <?php endif;?>

        <?php if(!is_numeric($key) and $action->method != 'view'):?>
        <td class='w-160px'>
          <?php /* Layout rules. */ ?>
          <div class='input-group'>
            <span class='input-group-addon text-muted'><?php echo $lang->workflowlayout->layoutRules;?></span>
            <?php echo html::select("subTables[$childModule][layoutRules][$key][]", $rules, $field->layoutRules, "id='layoutRules' class='form-control chosen' multiple='multiple' $disabled");?>
          </div>
        </td>

        <td class='w-240px'>
          <?php /* Default value. */ ?>
          <div class='input-group'>
            <span class='input-group-addon text-muted'><?php echo $lang->workflowlayout->defaultValue;?></span>
            <?php
            $data = "data-module='{$childModule}' data-field='{$field->field}'";
            if($field->control == 'checkbox')
            {
                echo html::select("subTables[$childModule][defaultValue][$key][]", $field->options, $field->defaultValue, "id='defaultValue{$childModule}{$key}' class='form-control picker-select' multiple $data $disabled");
            }
            else
            {
                echo html::select("subTables[$childModule][defaultValue][$key]", array('' => '') + $field->options, $field->defaultValue, "id='defaultValue{$childModule}{$key}' class='form-control picker-select' $data $disabled");
            }
            echo "<span class='input-group-addon fix-border'></span>";
            if(in_array($field->control, $dateControlList))
            {
                $class = 'form-' . $field->control;
                echo html::input("subTables[$childModule][defaultValue][$key]", ($field->defaultValue && $field->defaultValue != 'currentTime') ? $field->defaultValue : '', "id='defaultValue{$childModule}{$key}' class='form-control $class' $disabled");
            }
            else
            {
                echo html::input("subTables[$childModule][defaultValue][$key]", ($field->defaultValue && strpos(',currentUser,currentDept,currentTime,', ",$field->defaultValue,") === false) ? $field->defaultValue : '', "id='defaultValue{$childModule}{$key}' class='form-control' disabled='disabled'");
            }
            $checked = '';
            if(!in_array($field->control, $dateControlList)) $checked .= "disabled='disabled'";
            if(!in_array($field->control, $controlList)) $checked .= "checked='checked'";
            if(in_array($field->control, $dateControlList) && !empty($field->defaultValue) && !isset($field->options[$field->defaultValue])) $checked .= "checked='checked'";
            ?>
            <span class='input-group-addon'>
              <div class='checkbox-primary'>
                <input type='checkbox' name="subTables[<?php echo $childModule;?>][custom][<?php echo $key;?>]" value='1' <?php echo "$checked $disabled";?>/>
                <label><?php echo $lang->workflowlayout->custom;?></label>
              </div>
            </span>
          </div>
        </td>
        <?php endif;?>

        <?php /* Readonly. */ ?>
        <?php if($action->type == 'single' && !in_array($action->action, $config->workflowaction->readonlyActions) && $field->field != 'actions'):?>
        <td class='w-80px'>
          <div class='input-group'>
            <?php $checked = $field->readonly ? "checked='checked'" : '';?>
            <div class='checkbox-primary'>
              <input type='checkbox' name="subTables[<?php echo $childModule;?>][readonly][<?php echo $key;?>]" value='1' <?php echo "$checked $disabled";?>/>
              <label><?php echo $lang->workflowlayout->readonly;?></label>
            </div>
          </div>
        </td>
        <?php endif;?>

        <?php /* Display or not. */ ?>
        <td class='w-100px text-center'>
          <?php if($mode == 'edit'):?>
          <button type='button' class='btn btn-link show-hide'>
            <span class='label-show'><?php echo $lang->workflowlayout->show;?></span>
            <span class='text-muted'>/</span>
            <span class='label-hide'><?php echo $lang->workflowlayout->hide;?></span>
          </button>
          <?php else:?>
          <?php echo $show ? $lang->workflowlayout->show : $lang->workflowlayout->hide;?>
          <?php endif;?>
          <?php echo html::hidden("subTables[$childModule][show][$key]",  $show ? '1' : '0');?>
        </td>
      </tr>
      <?php endforeach;?>
      <?php /* End foreach of childFields. */ ?>
    </table>
  </div>
  <?php endforeach;?>
  <?php /* End foreach of subTables. */ ?>
</div>
