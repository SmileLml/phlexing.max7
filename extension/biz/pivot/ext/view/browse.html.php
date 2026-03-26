<?php
/**
 * The browse view file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dataset
 * @version     $Id: browse.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/tablesorter.html.php';?>
<?php js::set('groupID', $groupID);?>
<?php js::set('confirmDesign', $lang->pivot->confirm->design);?>
<div id='mainMenu' class='clearfix'>
  <div id="sidebarHeader">
    <div class="title" title="<?php echo $groupName;?>">
     <?php
     echo $groupName;
     if(!empty($groupID)) echo html::a($this->createLink('pivot', 'browse', "dimensionID=$dimensionID&groupID=0&orderBy=$orderBy&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage"), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted' data-app='{$this->app->tab}'");
     ?>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('pivot', 'preview', '', $lang->pivot->toPreview, '', "class='btn btn-secondary'");?>
    <?php common::printLink('pivot', 'create', "dimensionID=$dimensionID", '<i class="icon icon-plus"></i> ' . $lang->pivot->create, '', 'class="btn btn-primary iframe"', true, true);?>
  </div>
</div>
<div id="mainContent" class='main-row split-row fade main-table'>
  <div class="side-col" id='sidebar' data-min-width='235'>
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class='cell'>
      <?php if(empty($groupTree)):?>
      <hr class="space">
      <div class="text-center text-muted"><?php echo $lang->pivot->noGroup;?></div>
      <hr class="space">
      <?php else:?>
      <?php echo $groupTree;?>
      <div class="text-center">
        <?php common::printLink('tree', 'browsegroup', "dimensionID=$dimensionID&groupID=$groupID&type=pivot", $lang->pivot->manageGroup, '', "class='btn btn-info btn-wide'");?>
      </div>
      <?php endif;?>
    </div>
  </div>
  <div class='main-col' data-min-width='400'>
    <table class="table" id='pivotList'>
      <thead>
        <tr>
          <th class="c-id"><?php echo $lang->pivot->id;?></th>
          <th class="c-name"><?php echo $lang->pivot->name;?></th>
          <th class="c-openedByAB"><?php echo $lang->openedByAB;?></th>
          <th class="c-group"><?php echo $lang->pivot->group;?></th>
          <th class="c-desc"><?php echo $lang->pivot->desc;?></th>
          <th class="c-actions"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($pivots as $pivot):?>
        <?php
        $hasPriv   = common::hasPriv('pivot', 'design');
        $clickable = !$pivot->builtin;
        $class     = !empty($pivot->used) ? "btn-design" : '';
        ?>
        <tr>
          <td>
            <?php echo ($hasPriv and $clickable) ? html::a(inlink('design', "id=$pivot->id"), $pivot->id, '', "class='$class' title={$pivot->id}") : "<span class='unclickable' title={$pivot->id}>$pivot->id</span>";?>
          </td>
          <td class='c-name name-padding'>
            <?php echo ($hasPriv and $clickable) ? html::a(inlink('design', "id=$pivot->id"), $pivot->name, '', "class='$class' title={$pivot->name}") : "<span class='unclickable' title={$pivot->name}>$pivot->name</span>";?>
            <?php if($pivot->stage == 'draft') echo "<span class='label label-outline label-info draft-label label-position'>{$lang->pivot->draftIcon}</span>";?>
          </td>
          <td class='c-name' title='<?php echo zget($users, $pivot->createdBy);?>'><?php echo zget($users, $pivot->createdBy);?></td>
          <?php $groupLabels = '';?>
          <?php foreach(explode(',', $pivot->group) as $group):?>
          <?php if(zget($groups, $group, '')) $groupLabels .= zget($groups, $group) . ',';?>
          <?php endforeach;?>
          <?php $groupLabels = rtrim($groupLabels, ',');?>
          <td title="<?php echo $groupLabels;?>">
            <?php echo $groupLabels;?>
          </td>
          <td class='c-name' title='<?php echo $pivot->desc;?>'><?php echo $pivot->desc;?></td>
          <td class='c-actions'>
            <?php common::printIcon('pivot', 'design', "id=$pivot->id", $pivot, 'list', 'design', '', !empty($pivot->used) ? 'btn-design' : '', false, '', '', 0, $clickable);?>
            <?php common::printIcon('pivot', 'edit', "id=$pivot->id", $pivot, 'list', '', '', 'iframe', true, '', '', 0, $clickable);?>
            <?php common::printIcon('pivot', 'delete', "id=$pivot->id&confirm=no", $pivot, 'list', 'trash', 'hiddenwin', false, '', '', '', 0, $clickable);?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
