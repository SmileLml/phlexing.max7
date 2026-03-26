<?php
/**
 * The browse view file of workflowreport module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Dongdong Jia <jiadongdong@easycorp.ltd>
 * @package     workflowreport
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../workflow/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/chart.html.php';?>
<?php js::set('reportSize', sizeof($reports));?>
<?php js::set('moduleName', $module);?>
<?php js::set('id', $id);?>

<div class='space space-sm'></div>
<div class='main-row'>
  <div class='side-col'>
    <?php include '../../workflow/view/side.html.php';?>
  </div>
  <div class='main-col'>
    <div class="row">
      <div class="col-md-7">
        <div class="panel">
          <div class="panel-heading">
            <strong><?php echo $lang->workflowreport->preview;?></strong>
          </div>
          <div class="panel-body text-center" style="max-height: 726px;">
            <div class='noReport'>
              <?php
              echo $lang->workflowreport->tips->noReport;
              extCommonModel::printLink('workflowreport', 'create', "module=$flow->module", $lang->workflowreport->tips->toCreate, "data-toggle='modal'");
              ?>
            </div>
            <div class='existReport'></div>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="panel">
          <div class="panel-heading">
            <strong><?php echo $lang->workflowreport->property;?></strong>
            <div class="panel-actions pull-right">
              <?php extCommonModel::printLink('workflowreport', 'create', "module=$flow->module", '<i class="icon-plus"> </i> ' . $lang->workflowreport->create, "class='btn btn-primary' data-toggle='modal'");?>
            </div>
        </div>
        <div class="panel-body main-table">
          <table class="table">
            <thead>
              <tr>
                <th class='w-70px text-center'> <?php echo $lang->sort;?></th>
                <th><?php echo $lang->workflowreport->name;?></th>
                <th class='w-100px'><?php echo $lang->workflowreport->type;?></th>
                <th class='w-100px text-center'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <tbody class='sortable' id='reportList'>
              <?php foreach($reports as $report):?>
              <tr data-id='<?php echo $report->id;?>'>
                <td class='sort-handler text-center'><i class='icon icon-move text-muted'></i></td>
                <td title='<?php echo $report->name;?>'><?php echo $report->name;?></td>
                <td><?php echo zget($lang->workflowreport->typeList, $report->type, '');?></td>
                <td class='actions'>
                  <?php
                  extCommonModel::printLink('workflowreport', 'edit', "id=$report->id", $lang->edit, "class='edit' data-toggle='modal'");
                  extCommonModel::printLink('workflowreport', 'delete', "id=$report->id", $lang->delete, "class='deleter'");
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
