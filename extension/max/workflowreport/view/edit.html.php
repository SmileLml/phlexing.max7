<?php
/**
 * The edit view file of workflow module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Dongdong Jia <jiadongdong@easycorp.ltd>
 * @package     workflow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<form id="ajaxForm" method='post' action='<?php echo inlink('edit', "id=$report->id");?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->workflowreport->name;?></th>
      <td><?php echo html::input('name', $report->name, "class='form-control'");?></td>
      <td class='w-40px'></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowreport->type;?></th>
      <td><?php echo html::radio('type', $lang->workflowreport->iconList, $report->type);?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowreport->countType;?></th>
      <td><?php echo html::radio('countType', $lang->workflowreport->countTypeList, $report->countType);?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowreport->displayType;?></th>
      <td><?php echo html::radio('displayType', $lang->workflowreport->displayTypeList, $report->displayType);?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowreport->dimension;?></th>
      <td>
        <div class="input-group">
          <?php echo html::select('dimension', $dimension, $report->dimension, "class='form-control chosen'");?>
          <span class="input-group-addon fix-border fix-padding"></span>
          <?php echo html::select('granularity', $lang->workflowreport->granularityList, zget($report, 'granularity', 'month'), "class='form-control chosen'");?>
        </div>
      </td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowreport->fields;?></th>
      <td><div id='fieldsSelect'><?php echo html::select('fields[]', $fields, $report->fields, "multiple='multiple' class='form-control chosen'");?></div></td>
      <td></td>
    </tr>
    <tr>
      <th></th>
      <td class='form-actions'>
        <?php echo html::hidden('module', $report->module);?>
        <?php echo html::submitButton();?>
      </td>
      <td></td>
    </tr>
  </table>
</form>
<?php js::set('currentType', $report->type);?>
<?php js::set('controlPairs', $controlPairs);?>
<?php include '../../common/view/footer.modal.html.php';?>
