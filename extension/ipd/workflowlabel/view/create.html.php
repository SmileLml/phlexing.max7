<?php
/**
 * The create view file of workflowlabel module of ZDOO.
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
<?php js::set('moduleName', $module);?>
<form id='ajaxForm' method='post' action='<?php echo inlink('create', "module=$module");?>'>
  <?php if(empty($config->flow->workflowlabel->featureTipsClosers) || strpos($config->flow->workflowlabel->featureTipsClosers, ",{$this->app->user->account},") === false):?>
  <div class='alert alert-dismissable'>
    <span class='text-muted'><i class='icon-alert icon-md'></i> <?php echo $lang->workflowlabel->tips->features;?></span>
    <span class='pull-right remove' data-dismiss='alert' aria-hidden='true'><?php echo $lang->workflowlabel->tips->known;?></span>
  </div>
  <?php endif;?>
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
      <td class='required' colspan='2'><?php echo html::input('label', '', "class='form-control'")?></td>
      <td></td>
      <td></td>
    </tr>
    <tr data-key='1'>
      <th class='params'><?php echo $lang->workflowlabel->params;?></th>
      <td>
        <?php echo html::select('fields[1]', $fields, 'deleted', "class='form-control' disabled")?>
        <?php echo html::hidden('fields[1]', 'deleted');?>
      </td>
      <td>
        <?php echo html::select('operators[1]', $lang->workflowlabel->operatorList, '=', "class='form-control' disabled");?>
        <?php echo html::hidden('operators[1]', '=');?>
      </td>
      <td class='value'>
        <?php echo html::select('values[1]', $lang->workflowfield->default->options->deleted, '0', "class='form-control' disabled")?>
        <?php echo html::hidden('values[1]', '0');?>
        <?php echo html::hidden('values2[1]', '');?>
      </td>
      <td data-type='params'><a href='javascript:;' class='btn addItem'><i class='icon icon-plus'></i></a></td>
    </tr>
    <tr data-key='2'>
      <th></th>
      <td><?php echo html::select('fields[2]', $fields, '', "class='form-control chosen'");?></td>
      <td><?php echo html::select('operators[2]', $lang->workflowlabel->operatorList, '', "class='form-control chosen'");?></td>
      <td class='value'>
        <?php echo html::input('values[2]', '', "class='form-control'");?>
        <?php echo html::hidden('values2[2]');?>
      </td>
      <td data-type='params'>
        <a href='javascript:;' class='btn addItem'><i class='icon icon-plus'></i></a>
        <a href='javascript:;' class='btn delItem'><i class='icon icon-close'></i></a>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowlabel->orderBy;?></th>
      <td colspan='2'><?php echo html::select('orderFields[]', $fields, '', "class='form-control chosen'");?></td>
      <td><?php echo html::select('orderTypes[]', $lang->workflowlabel->orderTypeList, '', "class='form-control chosen'");?></td>
      <td data-type='orderBy'>
        <a href='javascript:;' class='btn addItem'><i class='icon icon-plus'></i></a>
      </td>
    </tr>
    <tr>
      <th></th>
      <td class='form-actions' colspan='4'>
        <?php echo html::hidden('module', $module);?>
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
$orderField = html::select('orderFields[]', $fields, '', "class='form-control chosen'");
$orderType  = html::select('orderTypes[]', $lang->workflowlabel->orderTypeList, '', "class='form-control chosen'");
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
js::set('key', 3);
js::set('paramsRow', $paramsRow);
js::set('orderByRow', $orderByRow);
?>
<?php include '../../common/view/footer.modal.html.php';?>
