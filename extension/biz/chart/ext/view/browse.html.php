<?php
/**
 * The browse view file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
<?php js::set('confirmDesign', $lang->chart->confirm->design);?>
<div id='mainMenu' class='clearfix'>
  <div id="sidebarHeader">
    <div class="title" title="<?php echo $groupName;?>">
     <?php
     echo $groupName;
     if(!empty($groupID)) echo html::a($this->createLink('chart', 'browse', "dimensionID=$dimensionID&groupID=0&orderBy=$orderBy&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage"), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted' data-app='{$this->app->tab}'");
     ?>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('chart', 'preview', '', $lang->chart->toPreview, '', "class='btn btn-secondary btn-toPreview'");?>
    <?php common::printLink('chart', 'create', "dimensionID=$dimensionID", '<i class="icon icon-plus"></i> ' . $lang->chart->create, '', 'class="btn btn-primary iframe"', true, true);?>
  </div>
</div>
<div id="mainContent" class='main-row split-row fade main-table'>
  <div class="side-col" id='sidebar' data-min-width='235'>
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class='cell'>
      <?php if(empty($groupTree)):?>
      <hr class="space">
      <div class="text-center text-muted"><?php echo $lang->chart->noGroup;?></div>
      <hr class="space">
      <?php else:?>
      <?php echo $groupTree;?>
      <div class="text-center">
        <?php common::printLink('tree', 'browsegroup', "dimensionID=$dimensionID&groupID=$groupID&type=chart", $lang->chart->manageGroup, '', "class='btn btn-info btn-wide'");?>
      </div>
      <?php endif;?>
    </div>
  </div>
  <div class='main-col' data-min-width='400'>
    <table class="table" id='chartList'>
      <thead>
        <tr>
          <th class="c-id"><?php echo $lang->chart->id;?></th>
          <th class="c-name"><?php echo $lang->chart->name;?></th>
          <th class="c-openedByAB"><?php echo $lang->openedByAB;?></th>
          <th class="c-group"><?php echo $lang->chart->group;?></th>
          <th class="c-type"><?php echo $lang->chart->type;?></th>
          <th class="c-desc"><?php echo $lang->chart->desc;?></th>
          <th class="c-actions"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($charts as $chart):?>
        <?php
        $hasPriv   = common::hasPriv('chart', 'design');
        $clickable = $this->chart->isClickable($chart, 'design');
        $class     = (!empty($chart->used) ? "btn-design" : '');
        ?>
        <tr>
          <td>
            <?php echo ($hasPriv and $clickable) ? html::a(inlink('design', "id=$chart->id"), $chart->id, '', "class='$class' title={$chart->id}") : "<span class='unclickable' title={$chart->id}>$chart->id</span>";?>
          </td>
          <td class='c-name name-padding'>
            <?php echo ($hasPriv and $clickable) ? html::a(inlink('design', "id=$chart->id"), $chart->name, '', "class='$class' title={$chart->name}") : "<span class='unclickable' title={$chart->name}>$chart->name</span>";?>
            <?php if($chart->stage == 'draft') echo "<span class='label label-outline label-info draft-label label-position'>{$lang->chart->draftIcon}</span>";?>
          </td>
          <td class='c-name' title='<?php echo zget($users, $chart->createdBy);?>'><?php echo zget($users, $chart->createdBy);?></td>
          <?php $groupLabels = '';?>
          <?php foreach(explode(',', $chart->group) as $group):?>
          <?php if(zget($groups, $group, '')) $groupLabels .= zget($groups, $group) . ',';?>
          <?php endforeach;?>
          <?php $groupLabels = rtrim($groupLabels, ',');?>
          <td title='<?php echo $groupLabels;?>'><?php echo $groupLabels;?></td>
          <td><?php echo zget($lang->chart->browseTypeList, $chart->type, $chart->type);?></td>
          <td class='c-name' title='<?php echo $chart->desc;?>'><?php echo $chart->desc;?></td>
          <td class='c-actions'>
            <?php common::printIcon('chart', 'design', "id=$chart->id", $chart, 'list', 'design', '', !empty($chart->used) ? 'btn-design' : '');?>
            <?php common::printIcon('chart', 'edit', "id=$chart->id", $chart, 'list', '', '', 'iframe', true);?>
            <?php common::printIcon('chart', 'delete', "id=$chart->id", $chart, 'list', 'trash', 'hiddenwin');?>
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
