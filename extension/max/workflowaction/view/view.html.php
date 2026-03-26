<?php
/**
 * The detail view file of workflowaction module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugan@cnezsoft.com>
 * @package     workflowaction
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<table class='table table-data'>
  <tr>
    <th class='w-70px'><?php echo $lang->workflowaction->name;?></th>
    <td><?php echo $action->name;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->workflowaction->action;?></th>
    <td><?php echo $action->action;?></td>
  </tr>
  <?php if(!empty($action->open)):?>
  <tr>
    <th><?php echo $lang->workflowaction->open;?></th>
    <td><?php echo zget($lang->workflowaction->openList, $action->open);?></td>
  </tr>
  <?php endif;?>
  <?php if(!empty($action->position)):?>
  <tr>
    <th><?php echo $lang->workflowaction->position;?></th>
    <td><?php echo zget($lang->workflowaction->positionList, $action->position);?></td>
  </tr>
  <?php endif;?>
  <?php if(!empty($action->show)):?>
  <tr>
    <th><?php echo $lang->workflowaction->show;?></th>
    <td><?php echo zget($lang->workflowaction->showList, $action->show);?></td>
  </tr>
  <?php endif;?>
  <tr>
    <th><?php echo $lang->workflowaction->createdBy;?></th>
    <td><?php echo zget($users, $action->createdBy) . $lang->at . formatTime($action->createdDate, DT_DATETIME1);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->workflowaction->desc;?></th>
    <td><?php echo $action->desc;?></td>
  </tr>
</table>
<?php echo $this->fetch('action', 'history', "objectType=workflowaction&objectID=$action->id");?>
    <div class='cell'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
