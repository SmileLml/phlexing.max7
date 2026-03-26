<?php
/**
 * The create view file of workflowfield module of ZDOO.
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
<?php js::set('hiddenPlaceholder', $config->workflowfield->hiddenPlaceholder)?>
<form method='post' id='ajaxForm' class='fieldForm' action='<?php echo inlink('create', "module=$flow->module");?>'>
  <table class='table table-form' id='fieldTable'>
    <tr>
      <th class='w-80px'><?php echo $lang->workflowfield->name;?></th>
      <td class='w-200px required'><?php echo html::input('name', '', "class='form-control'");?></td>
      <td class='w-60px'></td>
      <td></td>
    </tr>
    <tr class='field-tr'>
      <th class='w-80px'><?php echo $lang->workflowfield->field;?></th>
      <td class='required'><?php echo html::input('field', '', "class='form-control field-input' placeholder='{$lang->workflowfield->placeholder->code}'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowfield->control;?></th>
      <td>
        <?php unset($lang->workflowfield->controlTypeList['label']);?>
        <?php if($flow->type == 'table') unset($lang->workflowfield->controlTypeList['file']);?>
        <?php echo html::select('control', $lang->workflowfield->controlTypeList, 'input', "class='form-control chosen'");?>
      </td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowfield->type;?></th>
      <td>
        <select id='type' name='type' class='form-control'>
          <?php
          foreach($config->workflowfield->typeList as $type => $typeList)
          {
              foreach($typeList as $key => $value)
              {
                  $selected = $key == 'varchar' ? "selected='selected'" : '';
                  echo "<option class='{$type}' value='{$key}' {$selected}>{$value}</option>";
              }
          }
          ?>
        </select>
      </td>
      <td colspan='2'>
        <div class='input-group'>
          <span class='input-group-addon length'><?php echo $lang->workflowfield->length;?></span>
          <?php echo html::number('length', 255, "max='{$config->workflowfield->max->varcharLength}' min='{$config->workflowfield->min->varcharLength}' step='1' class='form-control length' placeholder='{$lang->workflowfield->placeholder->varcharLength}' title='{$lang->workflowfield->placeholder->varcharLength}'");?>
          <span class='input-group-addon digits'><?php echo $lang->workflowfield->integerDigits;?></span>
          <?php echo html::number('integerDigits', 10, "max='{$config->workflowfield->max->integerDigits}' min='{$config->workflowfield->min->integerDigits}' step='1' class='form-control digits' placeholder='{$lang->workflowfield->placeholder->integerDigits}' title='{$lang->workflowfield->placeholder->integerDigits}'");?>
          <span class='input-group-addon digits'><?php echo $lang->workflowfield->decimalDigits;?></span>
          <?php echo html::number('decimalDigits', 2, "max='{$config->workflowfield->max->decimalDigits}' min='{$config->workflowfield->min->decimalDigits}' step='1' class='form-control digits' placeholder='{$lang->workflowfield->placeholder->decimalDigits}' title='{$lang->workflowfield->placeholder->decimalDigits}'");?>
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
        <?php echo html::hidden('expression');?>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowfield->datasource;?></th>
      <td colspan='3'><?php echo html::select('optionType', $datasources, 'custom', "class='form-control chosen optionType'");?></td>
    </tr>
    <tr class='sqlTR'>
      <th><?php echo $lang->workflowfield->sql;?></th>
      <td colspan='3'><?php echo html::textarea('sql', '', "rows='4' class='form-control' placeholder='{$lang->workflowfield->placeholder->sql}'");?></td>
    </tr>
    <tr class='hide' id='varsTR'>
      <th><?php echo $lang->workflowfield->vars;?></th>
      <td colspan='3' id='varsTD'></td>
    </tr>
    <tr class='hide'>
      <th></th>
      <td colspan='3'><?php echo baseHTML::a(inlink('addSqlVar'), $lang->workflowfield->addVar, "class='btn' data-toggle='modal'");?></td>
    </tr>
    <tr id='optionTR'>
      <th><?php echo $lang->workflowfield->options;?></th>
      <td colspan='3'>
        <div class="input-group">
          <span class='statusKey input-group-addon'><?php echo $lang->workflowfield->key;?></span>
          <?php echo html::input('options[code][]', '', "class='form-control' placeholder='{$lang->workflowfield->placeholder->optionCode}'");?>
          <span class='input-group-addon'><?php echo $lang->workflowfield->value;?></span>
          <?php echo html::input('options[name][]', '', "class='form-control'");?>
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
        <div id='optionsDIV'></div>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowfield->defaultValue;?></th>
      <td colspan='3'><?php echo html::input('default', '', "class='form-control'");?></td>
    </tr>
    <tr class='tipInfoBox'>
      <th><?php echo $lang->workflowfield->tipInfo;?></th>
      <td colspan='3'><?php echo html::input('placeholder', '', "class='form-control'");?></td>
    </tr>
    <tr class='tipInfoBox'>
      <th></th>
      <td colspan='3' class='text-warning'><?php echo $lang->workflowfield->tips->placeholder;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowfield->rules;?></th>
      <td colspan='3'><?php echo html::select('rules[]', $rules, '', "multiple class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th></th>
      <td colspan='3' class='form-actions'><?php echo html::submitButton();?></td>
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
