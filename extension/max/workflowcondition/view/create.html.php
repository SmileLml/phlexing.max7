<?php
/**
 * The create view file of workflowcondition module of ZDOO.
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
<form id='createConditionForm' method='post' action='<?php echo inlink('create', "action=$action->id");?>'>
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
      <th><?php echo $lang->workflowcondition->type;?></th>
      <td colspan='3'><?php echo html::select('conditionType', $lang->workflowcondition->typeList, 'data', "class='form-control'");?></td>
    </tr>

    <?php /* SQL TR */ ?>
    <tr class='sqlTR'>
      <th class='text-right '><?php echo $lang->workflowcondition->sql;?></th>
      <td colspan='3' class='required'><?php echo html::textarea('sql', '', "rows='3' class='form-control' placeholder='{$lang->workflowcondition->placeholder->sql}'");?></td>
    </tr>
    <tr class='sqlTR'>
      <th class='text-right '><?php echo $lang->workflowcondition->result;?></th>
      <td colspan='3'><?php echo html::select('sqlResult', $lang->workflowcondition->resultList, 'empty', "class='form-control'");?></td>
    </tr>

    <?php /* Data TR */ ?>
    <tr class='dataTR' data-key='1'>
      <th class='text-right'>
        <?php echo $lang->workflowcondition->field;?>
        <?php echo html::hidden('logicalOperator[1]', '');?>
      </th>
      <td><?php echo html::select("field[1]", $fields, '', "class='form-control chosen'");?></td>
      <td><?php echo html::select("operator[1]", $lang->workflowcondition->operatorList, '', "class='form-control'");?></td>
      <td id='paramTD'><?php echo html::input("param[1]", '', "id='param1' class='form-control' autocomplete='off'");?></td>
      <td class='text-middle'>
        <?php echo baseHTML::a('javascript:;', "<i class='icon-plus icon-large'></i>",   "class='btn addCondition'");?>
      </td>
    </tr>

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
js::set('conditionKey', 2);
js::set('itemRow', $itemRow);
?>
<?php include '../../common/view/footer.modal.html.php';?>
