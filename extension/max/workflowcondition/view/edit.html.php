<?php
/**
 * The edit view file of workflowcondition module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowcondition
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php js::set('moduleName', $action->module);?>
<form id='editConditionForm' class='conditionForm' method='post' action='<?php echo inlink('edit', "action=$action->id&key=$key");?>'>
  <?php $condition = $action->conditions[$key];?>
  <?php if(empty($config->flow->workflowcondition->knowTips)):?>
  <div id='tips' class='alert'>
    <span><i class='icon icon-info'></i> <?php echo $lang->workflowcondition->tips;?></span>
    <?php echo baseHTML::a('javascript:;', $lang->workflowcondition->know);?>
  </div>
  <?php endif;?>
  <table class='table table-form' id='conditionTable'>
    <tr>
      <td class='w-80px'></td>
      <td class='w-220px'></td>
      <td class='w-80px'></td>
      <td></td>
      <td class='w-120px'></td>
    </tr>
    <tr>
      <th class='text-right'><?php echo $lang->workflowcondition->type;?></th>
      <td colspan='3'><?php echo html::select('conditionType', $lang->workflowcondition->typeList, $condition->conditionType, "class='form-control'");?></td>
    </tr>

    <?php /* SQL TR */ ?>
    <tr class='sqlTR'>
      <th class='text-right '><?php echo $lang->workflowcondition->sql;?></th>
      <td colspan='3' class='required'><?php echo html::textarea('sql', zget($condition, 'sql', ''), "rows='3' class='form-control' placeholder='{$lang->workflowcondition->placeholder->sql}'");?></td>
    </tr>
    <tr class='sqlTR'>
      <th class='text-right '><?php echo $lang->workflowcondition->result;?></th>
      <td colspan='3'><?php echo html::select('sqlResult', $lang->workflowcondition->resultList, zget($condition, 'sqlResult', 'empty'), "class='form-control'");?></td>
    </tr>

    <?php /* Data TR */ ?>
    <?php $key = 1;?>
    <?php if(!empty($condition->fields)):?>
    <?php foreach($condition->fields as $field):?>
    <tr class='dataTR' data-key='<?php echo $key;?>'>
      <th class='text-right'>
        <?php if($key == 1):?>
        <?php echo $lang->workflowcondition->field;?>
        <?php echo html::hidden("logicalOperator[$key]", zget($field, 'logicalOperator', 'and'));?>
        <?php else:?>
        <?php echo html::select("logicalOperator[$key]", $lang->workflowcondition->logicalOperatorList, zget($field, 'logicalOperator', 'and'), "class='form-control'");?>
        <?php endif;?>
      </th>
      <td><?php echo html::select("field[$key]", $fields, $field->field, "class='form-control chosen'");?></td>
      <td><?php echo html::select("operator[$key]", $lang->workflowcondition->operatorList, $field->operator, "class='form-control'");?></td>
      <td id='paramTD'>
        <?php $value       = helper::safe64Encode(urlencode($field->param));?>
        <?php $elementName = helper::safe64Encode(urlencode("param[$key]"));?>
        <?php echo $this->workflowfield->getFieldControl($action->module, $field->field, $value, $elementName);?>
      </td>
      <td class='text-middle'>
        <?php echo baseHTML::a('javascript:;', "<i class='icon-plus icon-large'></i>",   "class='btn addCondition'");?>
        <?php if($key > 1):?>
        <?php echo baseHTML::a('javascript:;', "<i class='icon-close icon-large'></i>", "class='btn delCondition'");?>
        <?php endif;?>
      </td>
    </tr>
    <?php $key++;?>
    <?php endforeach;?>
    <?php else:?>
    <tr class='dataTR' data-key='<?php echo $key;?>'>
      <th class='text-right'>
        <?php echo $lang->workflowcondition->field;?>
        <?php echo html::hidden("logicalOperator[$key]", '');?>
      </th>
      <td><?php echo html::select("field[$key]", $fields, '', "class='form-control chosen'");?></td>
      <td><?php echo html::select("operator[$key]", $lang->workflowcondition->operatorList, '', "class='form-control'");?></td>
      <td id='paramTD'><?php echo html::input("param[$key]", '', "id='param{$key}' class='form-control' autocomplete='off'");?></td>
      <td class='text-middle'>
        <?php echo baseHTML::a('javascript:;', "<i class='icon-plus icon-large'></i>",   "class='btn addCondition'");?>
      </td>
    </tr>
    <?php $key++;?>
    <?php endif;?>

    <tr>
      <th></th>
      <td class='form-actions' colspan='4'><?php echo html::submitButton();?></td>
    </tr>
  </table>
</form>
<?php
$field         = html::select("field[KEY]", $fields, '', "class='form-control chosen'");
$operator      = html::select("operator[KEY]", $lang->workflowcondition->operatorList, '', "class='form-control'");
$logicOperater = html::select('logicalOperator[KEY]', $lang->workflowcondition->logicalOperatorList, '', "class='form-control'");
$itemRow = <<<EOT
  <tr class='dataTR' data-key='KEY'>
    <th>{$logicOperater}</th>
    <td>{$field}</td>
    <td>{$operator}</td>
    <td id='paramTD'><input type="text" value= "" name="param[KEY]" id="paramKEY" class="form-control" autocomplete="off"></td>
    <td class='text-middle'>
      <a href="javascript:;" class="btn addCondition"><i class="icon-plus icon-large"></i></a>
      <a href="javascript:;" class="btn delCondition"><i class="icon-close icon-large"></i></a>
    </td>
  </tr>
EOT;
js::set('conditionKey', $key);
js::set('itemRow', $itemRow);
?>
<?php include '../../common/view/footer.modal.html.php';?>
