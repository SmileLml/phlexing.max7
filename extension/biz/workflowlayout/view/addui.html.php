<?php
/**
 * The addUI view file of workflowlayout module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     workflowlayout
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php js::set('moduleName', $module);?>
<form id='ajaxForm' target='hiddenwin' method='post' action='<?php echo inlink('addUI', "module={$module}&action={$action}");?>'>
  <table class='table table-form' id='conditionTable'>
    <tr>
      <td class='w-80px'></td>
      <td class='w-220px'></td>
      <td class='w-80px'></td>
      <td></td>
      <td class='w-120px'></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowlayout->ui->name;?></th>
      <td colspan='3'><?php echo html::input('name', '', "class='form-control'");?></td>
    </tr>

    <?php $this->loadModel('workflowfield');?>
    <?php $operatorList = $lang->workflowcondition->operatorList;?>
    <tr class='dataTR' data-key='1'>
      <th class='text-right'><?php echo $lang->workflowlayout->ui->condition;?></th>
      <td><?php echo html::select("field[1]", $fields, '', "class='form-control chosen'");?></td>
      <td><?php echo html::select("operator[1]", $operatorList, '', "class='form-control'");?></td>
      <td id='paramTD'><?php echo html::input("param[1]", '', "id='param1' class='form-control' autocomplete='off'");?></td>
      <td class='text-middle'><?php echo baseHTML::a('javascript:;', "<i class='icon-plus icon-large'></i>",   "class='btn addCondition'");?></td>
    </tr>

    <?php if($others):?>
    <tr><th class='text-left' colspan='4'><?php echo $lang->workflowlayout->ui->other;?></th></tr>
    <?php $preUI = 0;?>
    <?php foreach($others as $uiID => $uiConditions):?>
    <?php foreach($uiConditions as $fieldConditions):?>
    <?php foreach($fieldConditions as $condition):?>
    <tr>
      <th class='text-right'><?php echo $uiID != $preUI ? $uiList[$uiID]->name : ''; $preUI = $uiID;?></th>
      <td><?php echo html::select("otherfield", $fields, $condition->field, "class='form-control chosen' disabled");?></td>
      <td><?php echo html::select("otheroperator", $operatorList, $condition->operator, "class='form-control' disabled");?></td>
      <td id='paramTD'>
        <?php $value       = helper::safe64Encode(urlencode($condition->param));?>
        <?php $elementName = helper::safe64Encode(urlencode("param"));?>
        <?php echo $this->workflowfield->getFieldControl($module, $condition->field, $value, $elementName, '', 'disabled');?>
      </td>
      <td class='text-middle'></td>
    </tr>
    <?php endforeach;?>
    <?php endforeach;?>
    <?php endforeach;?>
    <?php endif;?>

    <tr>
      <td class='form-actions text-center' colspan='5'>
        <?php
        echo html::submitButton();
        echo html::a(inlink('admin', "module=$module&action=$action"), $lang->cancel, '', "class='btn btn-wide loadInModal iframe'");
        ?>
      </td>
    </tr>
  </table>
</form>
<?php
$field    = html::select("field[KEY]", $fields, '', "class='form-control chosen'");
$operator = html::select("operator[KEY]", $operatorList, '', "class='form-control'");
$itemRow  = <<<EOT
  <tr class='dataTR' data-key='KEY'>
    <th></th>
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
<script>
<?php echo file_get_contents(dirname(__FILE__, 2) . '/js/uicommon.js');?>
</script>
<?php include '../../common/view/footer.modal.html.php';?>
