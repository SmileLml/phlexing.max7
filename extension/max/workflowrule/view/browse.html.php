<?php
/**
 * The browse view file of workflowrule module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowrule
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='menuActions'>
  <?php extCommonModel::printLink('workflowrule', 'create', '', "<i class='icon icon-plus'> </i>" . $lang->workflowrule->create, "class='btn btn-primary' data-toggle='modal'");?>
</div>
<div class='main-table' data-ride='table'>
  <table class='table has-sort-head'>
    <thead>
      <tr>
        <?php $vars="&orderBy=%s";?>
        <th><?php commonModel::printOrderLink('name', $orderBy, $vars, $lang->workflowrule->name);?></th>
        <th><?php echo $lang->workflowrule->rule;?></th>
        <th class='w-120px'><?php commonModel::printOrderLink('type', $orderBy, $vars, $lang->workflowrule->type);?></th>
        <th class='w-120px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->workflowrule->createdBy);?></th>
        <th class='w-120px'><?php commonModel::printOrderLink('createdDate', $orderBy, $vars, $lang->workflowrule->createdDate);?></th>
        <th class='w-90px text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <?php foreach($rules as $rule):?>
      <tr>
        <td><?php echo $rule->name;?></td>
        <td><?php echo $rule->rule;?></td>
        <td><?php echo $lang->workflowrule->typeList[$rule->type];?></td>
        <td><?php echo zget($users, $rule->createdBy);?></td>
        <td><?php echo formatTime($rule->createdDate, DT_DATE1);?></td>
        <td class='actions'>
          <?php if($rule->type != 'system'):?>
          <?php extCommonModel::printLink('workflowrule', 'edit', "id=$rule->id", $lang->edit, "class='edit' data-toggle='modal'");?>
          <?php extCommonModel::printLink('workflowrule', 'delete', "id=$rule->id", $lang->delete, "class='deleter'");?>
          <?php endif;?>
        </td>
      </tr>
    <?php endforeach;?>
  </table>
  <div class='table-footer'><?php echo $pager->show('right', 'pagerjs');?></div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
