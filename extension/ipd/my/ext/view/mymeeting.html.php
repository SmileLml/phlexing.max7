<?php
/**
 * The myMeeting  of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     my
 * @version     $Id: myMeeing.html.php 4903 2021-06-12 18:48:59Z $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php'?>
<?php js::set('browseType', $browseType);?>
<?php js::set('mode', $mode);?>
<?php js::set('total', $pager->recTotal);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<?php js::set('todoCount', $todoCount);?>
<?php js::set('isMax', $isMax);?>
<?php js::set('isBiz', $isBiz);?>
<?php js::set('isOpenedURAndSR', $isOpenedURAndSR);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    foreach($lang->my->featureBar[$app->rawMethod]['myMeeting'] as $key => $name)
    {
        $label  = "<span class='text'>{$name}</span>";
        $label .= $key == $browseType ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';
        $active = $key == $browseType ? 'btn-active-text' : '';
        echo html::a(inlink($app->rawMethod, "mode=" . strtolower($mode) . "&type=$key"), $label, '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->meeting->byQuery;?></a>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=workMeeting');?>" data-module='workMeeting'></div>
<div id="mainContent" class="main-table">
  <?php if(empty($meetings)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->noData;?></span>
    </p>
  </div>
  <?php else:?>
  <table class="table has-sort-head" id='meetingList'>
    <?php $vars = "mode=" . strtolower($mode) . "&type=$browseType&param=$queryID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
    <thead>
      <tr>
        <th class='text-left w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->meeting->id);?></th>
        <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->meeting->name);?></th>
        <th class='w-120px'><?php common::printOrderLink('mode', $orderBy, $vars, $lang->meeting->mode);?></th>
        <th class='w-100px'><?php common::printOrderLink('dept', $orderBy, $vars, $lang->meeting->dept);?></th>
        <th class='c-project'><?php common::printOrderLink('project', $orderBy, $vars, $lang->meeting->project);?></th>
        <th class='c-execution'><?php common::printOrderLink('execution', $orderBy, $vars, $lang->meeting->execution);?></th>
        <th class='w-100px'><?php common::printOrderLink('date', $orderBy, $vars, $lang->meeting->date);?></th>
        <th class='c-room'><?php common::printOrderLink('room', $orderBy, $vars, $lang->meeting->room);?></th>
        <th class='w-80px'><?php common::printOrderLink('host', $orderBy, $vars, $lang->meeting->host);?></th>
        <th class='w-80px'><?php common::printOrderLink('minutedBy', $orderBy, $vars, $lang->meeting->minutedBy);?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($meetings as $meeting):?>
      <tr>
        <td><?php echo sprintf('%03d',$meeting->id);?></td>
        <td class='c-name' title="<?php echo $meeting->name;?>"><?php echo html::a($this->createLink('meeting', 'view', "meetingID=$meeting->id"), $meeting->name, '', "data-app='{$app->tab}'");?></td>
        <td><?php echo zget($lang->meeting->modeList, $meeting->mode);?></td>
        <td class='dept' title="<?php echo zget($depts, $meeting->dept);?>"><?php echo zget($depts, $meeting->dept);?></td>
        <td class='c-project' title="<?php echo zget($projects, $meeting->project);?>"><?php echo zget($projects, $meeting->project);?></td>
        <td class='c-execution' title="<?php echo zget($executions, $meeting->execution);?>"><?php echo zget($executions, $meeting->execution);?></td>
        <td><?php echo $meeting->date;?></td>
        <td title="<?php echo zget($rooms, $meeting->room);?>"><?php echo zget($rooms, $meeting->room, '')?></td>
        <td><?php echo zget($users, $meeting->host);?></td>
        <td><?php echo zget($users, $meeting->minutedBy);?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php'?>
