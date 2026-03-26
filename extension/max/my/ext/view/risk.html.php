<?php
/**
 * The risk view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('mode', $mode);?>
<?php js::set('total', $pager->recTotal);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<?php js::set('pageSummary', $lang->risk->pageSummary);?>
<?php js::set('todoCount', $todoCount);?>
<?php js::set('isMax', $isMax);?>
<?php js::set('isBiz', $isBiz);?>
<?php js::set('isOpenedURAndSR', $isOpenedURAndSR);?>
<style>
.pri-low {color: #313C52;}
.pri-middle {color: #FF9F46;}
.pri-high {color: #FB2B2B;}
</style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    foreach($lang->my->featureBar[$app->rawMethod] as $key => $name)
    {
        $label  = "<span class='text'>{$name}</span>";
        $label .= $key == $type ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';
        $active = $key == $type ? 'btn-active-text' : '';
        echo html::a(inlink($app->rawMethod, "mode=$mode&type=$key"), $label, '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->my->byQuery;?></a>
  </div>
</div>
<?php $module = $app->rawMethod == 'contribute' ? 'contributeRisk' : 'workRisk';?>
<div id="mainContent">
  <div class="cell<?php if($type == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', "module={$module}");?>" data-module=<?php echo $module;?>></div>
  <?php if(empty($risks)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
  </div>
  <?php else:?>
  <form id='myTaskForm' class="main-table table-risk" method="post">
    <table class="table has-sort-head table-fixed" id='risktable'>
      <?php $vars = "mode=$mode&type=$type&param=0&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
      <thead>
        <tr>
		  <th class='text-left w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->risk->id);?></th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->risk->name);?></th>
          <th class='text-left'><?php common::printOrderLink('project', $orderBy, $vars, $lang->my->ncProgram);?></th>
          <th class='w-80px text-center'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->risk->priAB);?></th>
          <th class='w-80px text-center'><?php common::printOrderLink('rate', $orderBy, $vars, $lang->risk->rate);?></th>
          <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->risk->status);?></th>
          <th class='w-120px'><?php common::printOrderLink('category', $orderBy, $vars, $lang->risk->category);?></th>
          <th class='w-120px'><?php common::printOrderLink('identifiedDate', $orderBy, $vars, $lang->risk->identifiedDate);?></th>
          <th class='w-120px'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->risk->assignedTo);?></th>
          <th class='w-80px'><?php common::printOrderLink('strategy', $orderBy, $vars, $lang->risk->strategy);?></th>
          <th class='w-200px text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $showDividing = ((common::hasPriv('risk', 'track') or common::hasPriv('risk', 'close') or common::hasPriv('risk', 'cancel') or common::hasPriv('risk', 'hangup')) and (common::hasPriv('risk', 'activate') or common::hasPriv('effort', 'createForObject') or common::hasPriv('risk', 'edit')));?>
        <?php foreach($risks as $risk):?>
        <tr data-id='<?php echo $risk->id ?>' data-status='<?php echo $risk->status?>'>
          <td><?php echo $risk->id;?></td>
          <td title="<?php echo $risk->name;?>"><?php echo html::a($this->createLink('risk', 'view', "riskID=$risk->id"), $risk->name, '', "data-group='project'");?></td>
          <td title="<?php echo zget($projectList, $risk->project, '');?>"><?php echo zget($projectList, $risk->project);?></td>
          <td class='text-center'><?php echo "<span class='pri-{$risk->pri}'>" . zget($lang->risk->priList, $risk->pri) . "</span>";?></td>
          <td class='text-center'><?php echo $risk->rate;?></td>
          <td class='status-risk status-<?php echo $risk->status?>'><?php echo zget($lang->risk->statusList, $risk->status);?></td>
          <td><?php echo zget($lang->risk->categoryList, $risk->category);?></td>
          <td><?php echo $risk->identifiedDate == '0000-00-00' ? '' : $risk->identifiedDate;?></td>
          <td><?php echo $this->risk->printAssignedHtml($risk, $users);;?></td>
          <td><?php echo zget($lang->risk->strategyList, $risk->strategy);?></td>
          <td class='c-actions'>
            <?php
            $params = "riskID=$risk->id";
            common::printIcon('risk', 'track', $params, $risk, "list", 'checked', '', 'iframe', true);
            common::printIcon('risk', 'close', $params, $risk, "list", '', '', 'iframe', true);
            common::printIcon('risk', 'cancel', $params, $risk, "list", '', '', 'iframe', true);
            common::printIcon('risk', 'hangup', $params, $risk, "list", 'pause', '', 'iframe', true);
            if($showDividing) echo '<div class="dividing-line"></div>';
            common::printIcon('risk', 'activate', $params, $risk, "list", '', '', 'iframe', true);
            common::printIcon('effort', 'createForObject', "objectType=risk&objectID=$risk->id", '', 'list', 'time', '', 'iframe', true, '', $lang->risk->effort);
            common::printIcon('risk', 'edit', $params, $risk, "list", '', '', 'iframe', true);
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <div class="table-statistic"></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<script>
$(function()
{
    $('#myTaskForm').table(
    {
        statisticCreator: function(table)
        {
            var $table       = table.getTable();
            var $checkedRows = $table.find(table.isDataTable ? '.datatable-row-left.checked' : 'tbody>tr.checked');
            var $originTable = table.isDataTable ? table.$.find('.datatable-origin') : null;
            var checkedTotal = $checkedRows.length;
            var $rows        = checkedTotal ? $checkedRows : $table.find(table.isDataTable ? '.datatable-rows .datatable-row-left' : 'tbody>tr');

            var checkedActive   = 0;
            var checkedHangup   = 0;
            $rows.each(function()
            {
                var $row = $(this);
                if($originTable) $row = $originTable.find('tbody>tr[data-id="' + $row.data('id') + '"]');

                var data = $row.data();

                if(data.status === 'active')   checkedActive++;
                if(data.status === 'hangup')   checkedHangup++;
            });

            return pageSummary.replace('%total%', $rows.length).replace('%active%', checkedActive).replace('%hangup%', checkedHangup);
        }
    })
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
