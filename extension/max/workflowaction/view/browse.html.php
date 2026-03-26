<?php
/**
 * The browse view file of workflowaction module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowaction
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../workflow/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<?php js::set('moduleName', $flow->module);?>
<?php js::set('setLayout', $lang->workflowaction->setLayout);?>
<div class='space space-sm'></div>
<div class='row'>
  <div class='col-md-6'>
    <div class='panel' id='previewArea'>
      <div class='panel-heading'></div>
      <div class='panel-body'>
        <div class='layout-buildin-tip text-center text-muted hide'><?php echo $lang->workflowaction->tips->buildin;?></div>
        <div class='layout-empty-tip text-center text-muted hide'><?php echo $lang->workflowaction->tips->emptyLayout;?></div>
        <div class='layout-no-tip text-center text-muted hide'><?php echo $lang->workflowaction->tips->noLayout;?></div>
        <div class='layout-preview hide'></div>
      </div>
    </div>
  </div>
  <div class='col-md-6'>
    <div class='panel main-table'>
      <div class='panel-heading'>
        <strong><?php echo $lang->workflowaction->settings;?> </strong>
        <div class='panel-actions'><?php extCommonModel::printLink('workflowaction', 'create', "module=$flow->module", "<i class='icon-plus'> </i>" . $lang->workflowaction->create, "class='btn btn-primary' data-toggle='modal' data-width='600'");?></div>
      </div>
      <div class='panel-body'>
        <?php
          $sortable = false;
          if(strpos($orderBy, 'order_asc')) foreach($actions as $action) if($action->extensionType == 'override') $sortable = true;
        ?>
        <table class='table has-sort-head table-fixed' id='actionListTable'>
          <thead>
            <tr>
              <?php $vars="&module=$flow->module&orderBy=%s";?>
              <?php if($sortable):?>
              <th class='w-50px text-center'> <?php echo $lang->sort;?></th>
              <?php endif;?>
              <th><?php commonModel::printOrderLink('name', $orderBy, $vars, $lang->workflowaction->name);?></th>
              <th><?php commonModel::printOrderLink('action', $orderBy, $vars, $lang->workflowaction->action);?></th>
              <?php if($flow->buildin):?>
              <th class='w-60px text-center'><?php commonModel::printOrderLink('buildin', $orderBy, $vars, $lang->workflowaction->buildin);?></th>
              <th class='w-80px text-center'><?php commonModel::printOrderLink('extensionType', $orderBy, $vars, $lang->workflowaction->extensionType);?></th>
              <?php endif;?>
              <th class='text-center' style='width: <?php echo $lang->workflowaction->actionWidth;?>px'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($actions as $action):?>
            <?php if($action->extensionType == 'override') continue;?>
            <?php $canSort = false;?>
            <?php include 'browse.tr.html.php';?>
            <?php endforeach;?>
          </tbody>
          <tbody class='<?php echo $sortable ? 'sortable' : '';?>' id='actionList'>
            <?php foreach($actions as $action):?>
            <?php if($action->extensionType != 'override') continue;?>
            <?php $canSort = true?>
            <?php include 'browse.tr.html.php';?>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
