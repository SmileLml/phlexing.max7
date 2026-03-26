<?php
/**
 * The edit view file of workflowlabel module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowlabel
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php js::set('moduleName', $label->module);?>
<form id='ajaxForm' method='post' action='<?php echo inlink('edit', "id=$label->id");?>'>
  <table class='table table-form'>
    <tr class='no-padding'>
      <td class='w-100px'></td>
      <td></td>
      <td class='w-100px'></td>
      <td class='w-220px'></td>
      <td class='w-100px'></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowlabel->label;?></th>
      <td colspan='2' class='required'><?php echo html::input('label', $label->label, "class='form-control'")?></td>
      <td></td>
      <td></td>
    </tr>

    <?php $key = 1;?>
    <?php foreach($label->params as $param):?>
    <tr data-key='<?php echo $key;?>'>
      <th><?php if($key == 1) echo $lang->workflowlabel->params;?></th>
      <?php if($param['field'] == 'deleted'):?>
      <td>
        <?php echo html::select("fields[$key]", $fields, 'deleted', "class='form-control' disabled")?>
        <?php echo html::hidden("fields[$key]", 'deleted');?>
      </td>
      <td>
        <?php echo html::select("operators[$key]", $lang->workflowlabel->operatorList, '=', "class='form-control' disabled");?>
        <?php echo html::hidden("operators[$key]", '=');?>
      </td>
      <td class='value'>
        <?php echo html::select("values[$key]", $lang->workflowfield->default->options->deleted, '0', "class='form-control' disabled")?>
        <?php echo html::hidden("values[$key]", '0');?>
        <?php echo html::hidden("values2[$key]");?>
      </td>
      <td data-type='params'>
        <a href='javascript:;' class='btn addItem'><i class='icon icon-plus'></i></a>
      </td>
      <?php else:?>
      <td><?php echo html::select("fields[$key]", $fields, $param['field'], "class='form-control'")?></td>
      <td><?php echo html::select("operators[$key]", $lang->workflowlabel->operatorList, $param['operator'], "class='form-control'");?></td>
      <td class='value'>
        <?php
        if(isset($param['value2'])) echo "<div class='input-group'>";

        $name  = helper::safe64Encode(urlencode("values[$key]"));
        $value = helper::safe64Encode(urlencode($param['value']));
        echo $this->workflowfield->getFieldControl($label->module, $param['field'], $value, $name);

        if(isset($param['value2']))
        {
            echo "<span class='input-group-addon values2'></span>";
            $name  = helper::safe64Encode(urlencode("values2[$key]"));
            $value = helper::safe64Encode(urlencode($param['value2']));
            echo $this->workflowfield->getFieldControl($label->module, $param['field'], $value, $name);
            echo '</div>';
        }
        else
        {
            echo html::hidden('values2[]');
        }
        ?>
      </td>
      <td data-type='params'>
        <a href='javascript:;' class='btn addItem'><i class='icon icon-plus'></i></a>
        <a href='javascript:;' class='btn delItem'><i class='icon icon-close'></i></a>
      </td>
      <?php endif;?>
    </tr>
    <?php $key++;?>
    <?php endforeach;?>

    <?php if(empty($label->orderBy)):?>
    <tr>
      <th><?php echo $lang->workflowlabel->orderBy;?></th>
      <td colspan='2'><?php echo html::select('orderFields[]', $fields, '', "class='form-control chosen'");?></td>
      <td><?php echo html::select('orderTypes[]', $lang->workflowlabel->orderTypeList, '', "class='form-control chosen'");?></td>
      <td data-type='orderBy'>
        <a href='javascript:;' class='btn addItem'><i class='icon icon-plus'></i></a>
      </td>
    </tr>
    <?php else:?>
    <?php $i = 1;?>
    <?php foreach($label->orderBy as $orderBy):?>
    <tr>
      <th><?php if($i == 1) echo $lang->workflowlabel->orderBy;?></th>
      <td colspan='2'><?php echo html::select('orderFields[]', $fields, $orderBy['field'], "class='form-control chosen'")?></td>
      <td><?php echo html::select('orderTypes[]', $lang->workflowlabel->orderTypeList, $orderBy['type'], "class='form-control chosen'");?></td>
      <td data-type='orderBy'>
        <a href='javascript:;' class='btn addItem'><i class='icon icon-plus'></i></a>
        <?php if($i > 1):?>
        <a href='javascript:;' class='btn delItem'><i class='icon icon-close'></i></a>
        <?php endif;?>
      </td>
    </tr>
    <?php $i++;?>
    <?php endforeach;?>
    <?php endif;?>

    <tr>
      <th></th>
      <td class='form-actions' colspan='4'>
        <?php echo html::submitButton();?>
      </td>
    <tr>
  </table>
</form>

<?php
$field      = html::select('fields[KEY]', $fields, '', "class='form-control chosen'");
$operator   = html::select('operators[KEY]', $lang->workflowlabel->operatorList, '', "class='form-control chosen'");
$value      = html::input('values[KEY]', '', "class='form-control'");
$value2     = html::hidden('values2[KEY]');
$orderField = html::select('orderFields[KEY]', $fields, '', "class='form-control chosen'");
$orderType  = html::select('orderTypes[KEY]', $lang->workflowlabel->orderTypeList, '', "class='form-control chosen'");
$paramsRow  = <<<EOT
  <tr data-key='KEY'>
    <th></th>
    <td>{$field}</td>
    <td>{$operator}</td>
    <td class='value'>
      {$value}
      {$value2}
    </td>
    <td data-type='params'>
      <a href="javascript:;" class="btn addItem"><i class="icon icon-plus"></i></a>
      <a href="javascript:;" class="btn delItem"><i class="icon icon-close"></i></a>
    </td>
  </tr>
EOT;
$orderByRow = <<<EOT
  <tr>
    <th></th>
    <td colspan='2'>{$orderField}</td>
    <td>{$orderType}</td>
    <td data-type='orderBy'>
      <a href="javascript:;" class="btn addItem"><i class="icon icon-plus"></i></a>
      <a href="javascript:;" class="btn delItem"><i class="icon icon-close"></i></a>
    </td>
  </tr>
EOT;
js::set('key', $key);
js::set('paramsRow', $paramsRow);
js::set('orderByRow', $orderByRow);
?>
<?php include '../../common/view/footer.modal.html.php';?>
