<?php
/**
 * The browse view file of activity module of ZenTaoQC.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     activity
 * @version     $Id: browse.html.php 5102 2020-09-09 14:39:54Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<?php js::set('orderBy', $orderBy);?>
<div id="mainContent" class="main-row">
  <div class='side-col' id='sidebar'>
    <div class='cell'><?php include '../../process/view/menu.html.php';?></div>
  </div>
  <div class="main-col">
    <div class='main-header clearfix'>
      <div class='btn-toolbar pull-left'>
        <strong><?php echo $lang->activity->browse?></strong>
        <a class="querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->activity->byQuery;?></a>
      </div>

     <div class="btn-toolbar pull-right">
      <div class='btn-group dropdown'>
        <?php common::printLink('activity', 'create', "", "<i class='icon icon-plus'></i>" . $lang->activity->create, '', "class='btn btn-primary'");?>
        <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
        <ul class='dropdown-menu pull-right'>
          <li><?php echo html::a($this->createLink('activity', 'create', "model=$browseType"), $lang->activity->create);?></li>
          <li><?php echo html::a($this->createLink('activity', 'batchCreate', "model=$browseType"), $lang->activity->batchCreate);?></li>
        </ul>
      </div>
     </div>

    </div>
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='activity' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=activity');?>"></div>
      <?php if(empty($activities)):?>
      <div class="table-empty-tip">
        <?php echo $lang->noData;?>
        <?php echo html::a($this->createLink('activity', 'create'), '<i class="icon icon-plus"></i> ' . $lang->activity->create, '', 'class="btn btn-info"')?>
      </div>
      <?php else:?>
      <form id='activityForm' data-ride="table" method='post' class="main-table">
        <table class='table has-sort-head' id="activityTable">
          <?php $vars = "model=$model&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <thead>
            <tr>
              <th class="text-left w-80px">
                <?php common::printOrderLink('id', $orderBy, $vars, $lang->activity->id);?>
              </th>
              <th class="w-100px"><?php common::printOrderLink('process',     $orderBy, $vars, $lang->activity->process);?></th>
              <th class="c-name"> <?php common::printOrderLink('name',        $orderBy, $vars, $lang->activity->name);?></th>
              <th class="w-100px"><?php common::printOrderLink('optional',    $orderBy, $vars, $lang->activity->optional);?></th>
              <th class="w-150px"><?php common::printOrderLink('tailorNorm',  $orderBy, $vars, $lang->activity->tailorNorm);?></th>
              <th class="text-center w-90px"><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->activity->assignedTo);?></th>
              <th class="w-80px"><?php common::printOrderLink('createdBy', $orderBy, $vars, $lang->activity->createdBy);?></th>
              <th class="w-100px"><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->activity->createdDate);?></th>
              <th class="text-center c-actions-4"><?php echo $lang->activity->actions;?></th>
              <th class="sort-default text-left w-50px"><?php echo $lang->activity->sort;?></th>
            </tr>
          </thead>
          <tbody class="sortable" id="orderTableList" style="position: static;">
            <?php foreach($activities as $activity):?>
            <tr data-id=<?php echo $activity->id;?> data-order=<?php echo $activity->order;?>>
              <td calss="c-id" title="<?php echo $activity->id;?>">
                <?php echo html::a($this->createLink('activity', 'view', "activityID={$activity->id}"), $activity->id);?>
              </td>
              <td title="<?php echo zget($processes, $activity->process, '');?>"><?php echo zget($processes, $activity->process, '');?></td>
              <td class="c-name" title="<?php echo $activity->name;?>"><?php echo html::a($this->createLink('activity', 'view', "activityID={$activity->id}"), $activity->name);?></td>
              <td><?php echo zget($lang->activity->optionalOptions, $activity->optional, '');?></td>
              <td class="c-name" title="<?php echo $activity->tailorNorm;?>"><?php echo $activity->tailorNorm;?></td>
              <td><?php echo $this->activity->printAssignedHtml($activity, $users);?></td>
              <td><?php echo zget($users, $activity->createdBy);?></td>
              <td><?php echo substr($activity->createdDate, 0, 11);?></td>
              <td class='c-actions text-center'>
                <?php
                    common::printIcon('zoutput',  'batchCreate', "activityID={$activity->id}", $activity, 'list', 'treemap-alt', '',          '',       '',    '', $lang->activity->output);
                    common::printIcon('activity', 'outputList',  "activityID={$activity->id}", $activity, 'list', 'list-alt',    '',          'iframe', 'yes', '', $lang->activity->outputList);
                    common::printIcon('activity', 'edit',        "activityID=$activity->id",   $activity, 'list', 'edit',        '',          '',       '',    '', $lang->activity->edit);
                    common::printIcon('activity', 'delete',      "activityID=$activity->id",   $activity, 'list', 'trash',       'hiddenwin', '',       '',    '', $lang->activity->delete);
                ?>
              </td>
              <td class="sort-handler">
                <i class="icon icon-move"></i>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <div class='table-footer'>
          <?php $pager->show('right', 'pagerjs');?>
        </div>
      </form>
      <?php endif; ?>
    </div>
  </div>
</div>
<script>
$(function()
{
    $('#orderTableList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('activity', 'updateOrder'), {'activity' : list, 'orderBy' : orderBy});
    });
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
