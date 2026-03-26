<?php
/**
 * The edit view file of wordflowfield module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowfield
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<?php include './expression.html.php';?>
<?php js::set('fieldID', $field->id);?>
<?php js::set('hiddenPlaceholder', $config->workflowfield->hiddenPlaceholder);?>
<form method='post' id='editFieldForm' class='fieldForm' action='<?php echo inlink('edit', "module=$field->module&id=$field->id");?>'>
  <table class='table table-form' id='fieldTable'>
    <?php
    /* 内置字段中subStatus可以编辑，其他不可编辑。默认字段中status、subStatus可以编辑，其他不可编辑。非内置且非默认字段可以编辑。*/
    /* The subStatus in the built-in field can be edited, and the others cannot be edited. The status, status, and status in the default field can be edited, and others cannot be edited. Non-built-in and non-default fields can be edited. */
    ?>
    <tr>
      <th class='w-80px'><?php echo $lang->workflowfield->name;?></th>
      <td class='w-200px'><?php echo html::input('name', $field->name, "class='form-control'" . ($field->role == 'quote' ? " disabled='disabled'" : ''));?></td>
      <td class='w-60px'></td>
      <td></td>
    </tr>
    <?php if(!$field->readonly):?>
    <tr>
      <th><?php echo $lang->workflowfield->field;?></th>
      <td>
        <?php echo html::input('field', $field->field, "class='form-control' placeholder='{$lang->workflowfield->placeholder->code}' disabled='disabled'");?>
      </td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowfield->control;?></th>
      <td>
        <?php
        unset($lang->workflowfield->controlTypeList['label']);
        $disabled = ($field->field == 'status' or $field->field == 'subStatus' or $this->config->db->driver != 'mysql') ? "disabled='disabled'" : '';
        if($field->role == 'quote') $disabled = 'disabled="disabled"';
        if($disabled) echo html::hidden('control', $field->control);
        echo html::select('control', $lang->workflowfield->controlTypeList, $field->control, "class='form-control chosen' onchange='showTips()' $disabled");
        ?>
      </td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowfield->type;?></th>
      <td>
        <?php if($disabled) echo html::hidden('type', $field->type);?>
        <select id='type' name='type' <?php echo $disabled;?> class='form-control'>
          <?php
          foreach($config->workflowfield->typeList as $type => $typeList)
          {
              foreach($typeList as $key => $value)
              {
                  $selected = $key == $field->type ? "selected='selected'" : '';
                  echo "<option class='{$type}' value='{$key}' {$selected}>{$value}</option>";
              }
          }
          ?>
        </select>
      </td>
      <td colspan='2'>
        <div class='input-group'>
          <?php
          if($field->type == 'decimal')
          {
              list($integerDigits, $decimalDigits) = explode(',', $field->length);
          }
          else
          {
              $integerDigits = $config->workflowfield->default->integerDigits;
              $decimalDigits = $config->workflowfield->default->decimalDigits;
          }
          ?>
          <span class='input-group-addon length'><?php echo $lang->workflowfield->length;?></span>
          <?php if($disabled) echo html::hidden('length', $field->length);?>
          <?php echo html::number('length', $field->length, "max='{$config->workflowfield->max->varcharLength}' min='{$config->workflowfield->min->varcharLength}' step='1' class='form-control length' placeholder='{$lang->workflowfield->placeholder->varcharLength}' title='{$lang->workflowfield->placeholder->varcharLength}' $disabled");?>
          <span class='input-group-addon digits'><?php echo $lang->workflowfield->integerDigits;?></span>
          <?php if($disabled) echo html::hidden('integerDigits', $integerDigits);?>
          <?php echo html::number('integerDigits', $integerDigits, "max='{$config->workflowfield->max->integerDigits}' min='{$config->workflowfield->min->integerDigits}' step='1' class='form-control digits' placeholder='{$lang->workflowfield->placeholder->integerDigits}' title='{$lang->workflowfield->placeholder->integerDigits}' $disabled");?>
          <span class='input-group-addon digits'><?php echo $lang->workflowfield->decimalDigits;?></span>
          <?php if($disabled) echo html::hidden('decimalDigits', $decimalDigits);?>
          <?php echo html::number('decimalDigits', $decimalDigits, "max='{$config->workflowfield->max->decimalDigits}' min='{$config->workflowfield->min->decimalDigits}' step='1' class='form-control digits' placeholder='{$lang->workflowfield->placeholder->decimalDigits}' title='{$lang->workflowfield->placeholder->decimalDigits}' $disabled");?>
        </div>
      </td>
    </tr>
    <tr class='hide'>
      <th></th>
      <td colspan='3' class='dataTip text-warning'></td>
    </tr>
    <tr class='hide'>
      <th><?php echo $lang->workflowfield->expression;?></th>
      <td colspan='3'>
        <div class='expression'></div>
        <?php echo baseHTML::a('javascript:;', $lang->workflowfield->formula->set, "class='set-expression'");?>
        <?php echo html::hidden('expression', $field->expression);?>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowfield->datasource;?></th>
      <td colspan='3'>
        <?php
        $disabled = $field->field == 'subStatus' ? "disabled='disabled'" : '';
        if($field->role == 'quote') $disabled = 'disabled="disabled"';
        $optionType = (is_array($field->options)) ? 'custom' : $field->options;
        if($disabled) echo html::hidden('optionType', $optionType);
        echo html::select('optionType', $datasources, $optionType, "class='form-control chosen optionType' onchange='showTips()' $disabled");
        ?>
      </td>
    </tr>
    <tr class='sqlTR'>
      <th><?php echo $lang->workflowfield->sql;?></th>
      <td colspan='3'><?php echo html::textarea('sql', $field->sql, "rows='4' class='form-control' placeholder='{$lang->workflowfield->placeholder->sql}'");?></td>
    </tr>
    <tr class='hide' id='varsTR'>
      <th><?php echo $lang->workflowfield->vars;?></th>
      <td colspan='3' id='varsTD'>
        <?php foreach($field->sqlVars as $var):?>
        <div id='<?php echo $var->varName;?>' class='w-p45 varControl'>
          <div class='input-group'>
            <?php echo $this->fetch('workflow', 'buildVarControl', "varName={$var->varName}");?>
          </div>
        </div>
        <?php endforeach;?>
      </td>
    </tr>
    <tr class='hide'>
      <th></th>
      <td colspan='3'><?php echo baseHTML::a(inlink('addSqlVar'), $lang->workflowfield->addVar, "class='btn' data-toggle='modal'");?></td>
    </tr>
    <?php if($field->field == 'subStatus'):?>
    <tr id='optionTR'>
      <th><?php echo $lang->workflowfield->options;?></th>
      <td colspan='3' class='subStatusTd'>
        <?php $statusList  = array();?>
        <?php $statusField = $this->workflowfield->getByField($flow->module, 'status');?>
        <?php if($statusField) $statusList = $this->workflowfield->getFieldOptions($statusField, $emptyOption = false);?>
        <?php if(!$statusList):?>
        <strong class='text-red'><?php echo $lang->workflowfield->tips->emptyStatus;?></strong>
        <?php else:?>
        <table class='table table-form table-bordered'>
          <thead>
            <tr class='text-center'>
              <th class='w-100px'><?php echo $lang->workflowfield->status;?></th>
              <th><?php echo $lang->workflowfield->subStatus;?></th>
            </tr>
          </thead>
          <?php foreach($statusList as $parentCode => $parentName):?>
          <?php if(!$parentName) continue;?>
          <tr>
            <td class='text-center'>
              <?php echo $parentName;?>
              <?php echo html::hidden('parentCode[]', $parentCode);?>
              <?php echo html::hidden('parentName[]', $parentName);?>
            </td>
            <td class='sortTd'>
              <?php $subStatus = zget($field->options, $parentCode, null);?>
              <?php $default   = zget($subStatus, 'default', '');?>
              <?php $options   = zget($subStatus, 'options', array());?>
              <?php if(empty($options)) $options = array('' => '');?>
              <?php if(is_array($options)):?>
              <?php $hiddenSort = count($options) == 1 ? 'hidden' : '';?>
              <?php foreach($options as $code => $name):?>
              <div class='input-group'>
                <span class='statusKey input-group-addon'><?php echo $lang->workflowfield->key;?></span>
                <?php echo html::input("optionCode[$parentCode][]", $code, "class='form-control' placeholder='{$lang->workflowfield->placeholder->optionCode}'");?>
                <span class='input-group-addon'><?php echo $lang->workflowfield->value;?></span>
                <?php echo html::input("optionName[$parentCode][]", $name, "class='form-control'");?>
                <span class='input-group-btn sort-btn <?php echo $hiddenSort?>'>
                  <a href='javascript:;' class='btn btn-default sortItem'><i class='icon-move'></i></a>
                </span>
                <span class='input-group-btn'>
                  <a href='javascript:;' class='btn btn-default addItem'><i class='icon-plus'></i></a>
                </span>
                <span class='input-group-btn'>
                  <a href='javascript:;' class='btn btn-default delItem'><i class='icon-minus'></i></a>
                </span>
                <span class='input-group-addon'>
                  <?php echo html::radio("optionDefault[$parentCode]", array($code => $lang->workflowfield->defaultSubStatus), $default);?>
                </span>
              </div>
              <?php endforeach;?>
              <?php else:?>
              <div class='input-group'>
                <span class='statusKey input-group-addon'><?php echo $lang->workflowfield->key;?></span>
                <?php echo html::input("optionCode[$parentCode][]", '', "class='form-control' placeholder='{$lang->workflowfield->placeholder->optionCode}'");?>
                <span class='input-group-addon'><?php echo $lang->workflowfield->value;?></span>
                <?php echo html::input("optionName[$parentCode][]", '', "class='form-control'");?>
                <span class='input-group-btn sort-btn hidden'>
                  <a href='javascript:;' class='btn btn-default sortItem'><i class='icon-move'></i></a>
                </span>
                <span class='input-group-btn'>
                  <a href='javascript:;' class='btn btn-default addItem'><i class='icon-plus'></i></a>
                </span>
                <span class='input-group-btn'>
                  <a href='javascript:;' class='btn btn-default delItem'><i class='icon-minus'></i></a>
                </span>
                <span class='input-group-addon'>
                  <?php echo html::radio("optionDefault[$parentCode]", array('default' => $lang->workflowfield->defaultSubStatus));?>
                </span>
              </div>
              <?php endif;?>
              <div id='optionDefault<?php echo $parentCode;?>'></div>
            </td>
          </tr>
          <?php endforeach;?>
        </table>
        <?php endif;?>
        <div id='optionsDIV'></div>
      </td>
    </tr>
    <?php else:?>
    <tr id='optionTR'>
      <th><?php echo $lang->workflowfield->options;?></th>
      <td colspan='3' class='sortTd'>
        <?php $options = array('' => '');?>
        <?php if(is_array($field->options)) $options = $field->options + array('' => '');?>
        <?php foreach($options as $code => $name):?>
        <div class="input-group">
          <span class='statusKey input-group-addon'><?php echo $lang->workflowfield->key;?></span>
          <?php echo html::input('options[code][]', $code, "class='form-control' placeholder='{$lang->workflowfield->placeholder->optionCode}'");?>
          <span class='input-group-addon'><?php echo $lang->workflowfield->value;?></span>
          <?php echo html::input('options[name][]', $name, "class='form-control'");?>
          <span class='input-group-btn sort-btn'>
            <a href='javascript:;' class='btn btn-default sortItem'><i class='icon-move'></i></a>
          </span>
          <span class='input-group-btn'>
            <a href='javascript:;' class='btn btn-default addItem'><i class='icon-plus'></i></a>
          </span>
          <span class='input-group-btn'>
            <a href='javascript:;' class='btn btn-default delItem'><i class='icon-minus'></i></a>
          </span>
        </div>
        <?php endforeach;?>
        <div id='optionsDIV'></div>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowfield->defaultValue;?></th>
      <td colspan='3'><?php echo html::input('default', $field->default, "class='form-control'");?></td>
    </tr>
    <tr class='tipInfoBox'>
      <th><?php echo $lang->workflowfield->tipInfo;?></th>
      <td colspan='3'><?php echo html::input('placeholder', $field->placeholder, "class='form-control'");?></td>
    </tr>
    <tr class='tipInfoBox'>
      <th></th>
      <td colspan='3' class='text-warning'><?php echo $lang->workflowfield->tips->placeholder;?></td>
    </tr>
    <?php endif;?>
    <tr>
      <th><?php echo $lang->workflowfield->rules;?></th>
      <td colspan='3'><?php echo html::select('rules[]', $rules, $field->rules, "multiple class='chosen form-control'");?></td>
    </tr>
    <?php endif;?>
    <?php if($groupPairs):?>
    <tr>
      <td colspan='4'>
        <?php
        $groupName = implode(',', $groupPairs);

        $tips = $lang->workflowfield->tips->syncQuoteNoOptions;
        if(in_array($field->control, $config->workflowfield->optionControls) && is_array($field->options)) $tips = $lang->workflowfield->tips->syncQuoteFields;
        $tips = sprintf($tips, $groupName);

        echo js::set('syncQuoteNoOptions', $lang->workflowfield->tips->syncQuoteNoOptions);
        echo js::set('syncQuoteFields', $lang->workflowfield->tips->syncQuoteFields);
        echo js::set('joinedGroupName', $groupName);
        echo "<div id='tipsBox' class='alert alert-warning'>{$tips}</div>";
        ?>
      </td>
    </tr>
    <?php endif;?>
    <tr>
      <th></th>
      <td colspan='3' class='form-actions'>
        <?php echo html::submitButton();?>
        <div id='alertDIV' class='hide'>
          <div id='alert' class='alert alert-warning'></div>
          <?php echo baseHTML::commonButton($lang->determine, 'btn btn-default', "id='alertBtn'");?>
        </div>
      </td>
    </tr>
  </table>
</form>
<div id='varGroup' class='hide'>
  <div id='key' class='w-p45 varControl'>
    <div class='input-group'>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.modal.html.php';?>
