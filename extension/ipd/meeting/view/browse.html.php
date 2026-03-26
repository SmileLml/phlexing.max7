<?php
/**
 * The browse of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Liyuchun <liyuchun@easycorp.ltd>
 * @package     meeting
 * @version     $Id: browse.html.php 4903 2021-06-09 13:48:59Z $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . "common/view/header.html.php"?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toobar pull-left">
    <?php
    $menus = customModel::getFeatureMenu($app->rawModule, $app->rawMethod);
    foreach($menus as $menuItem)
    {
        $active = $menuItem->name == $browseType ? ' btn-active-text' : '';
        $link   = $app->tab == 'my' ? $this->createLink('my', 'meeting', "browseType=$menuItem->name") : $this->createLink($this->moduleName, $this->methodName, "projectID=$projectID&from=$from&browseType=$menuItem->name");
        echo html::a($link, "<span class='text'>{$menuItem->text}</span>", '', "class='btn btn-link $active' data-app='{$app->tab}'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->meeting->byQuery;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php $extra = isset($projectID) ? "projectID=$projectID&from=$from" : '';?>
    <?php if(common::canModify($from, $project) && !empty($projectID)) common::printLink('meeting', 'create', $extra, "<i class='icon icon-plus'></i>" . $lang->meeting->create, '', "class='btn btn-primary' data-app='{$app->tab}'");?>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=meeting');?>" data-module='meeting'></div>
<div id="mainContent" class="main-table">
  <?php if(empty($meetings)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->noData;?></span>
      <?php if(common::hasPriv('meeting', 'create') && !empty($projectID)):?>
      <?php echo html::a($this->createLink('meeting', 'create', $extra), "<i class='icon icon-plus'></i> " . $lang->meeting->create, '', "class='btn btn-info' data-app='{$app->tab}'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class="table has-sort-head" id='meetingList'>
    <?php $vars = $this->app->tab == 'my' ? "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}" : "projectID=$projectID&from=$from&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
    <thead>
      <tr>
        <th class='text-left c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->meeting->id);?></th>
        <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->meeting->name);?></th>
        <?php if($app->tab == 'project' and isset($project->model) and $project->model == 'waterfall'):?>
        <th class='c-type'><?php common::printOrderLink('type', $orderBy, $vars, $lang->meeting->type);?></th>
        <?php endif;?>
        <th class='c-mode'><?php common::printOrderLink('mode', $orderBy, $vars, $lang->meeting->mode);?></th>
        <th class='c-dept'><?php common::printOrderLink('dept', $orderBy, $vars, $lang->meeting->dept);?></th>
        <th class='c-project'><?php common::printOrderLink('project', $orderBy, $vars, $lang->meeting->project);?></th>
        <th class='c-execution'><?php common::printOrderLink('execution', $orderBy, $vars, $lang->meeting->execution);?></th>
        <th class='c-date'><?php common::printOrderLink('date', $orderBy, $vars, $lang->meeting->date);?></th>
        <th class='c-room'><?php common::printOrderLink('room', $orderBy, $vars, $lang->meeting->room);?></th>
        <th class='c-participant'><?php common::printOrderLink('participant', $orderBy, $vars, $lang->meeting->host);?></th>
        <th class='c-minutedBy'><?php common::printOrderLink('minutedBy', $orderBy, $vars, $lang->meeting->minutedBy);?></th>
        <th class='c-actions-2'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($meetings as $meeting):?>
      <tr>
        <td><?php echo sprintf('%03d',$meeting->id);?></td>
        <td class='c-name' title="<?php echo $meeting->name;?>"><?php echo html::a($this->createLink('meeting', 'view', "meetingID=$meeting->id" . "&from=$from"), $meeting->name, '', "data-app={$app->tab}");?></td>
        <?php if($app->tab == 'project' and isset($project->model) and $project->model == 'waterfall'):?>
        <td><?php echo zget($typeList, $meeting->type);?></td>
        <?php endif;?>
        <td><?php echo zget($lang->meeting->modeList, $meeting->mode);?></td>
        <td class='dept' title="<?php echo zget($depts, $meeting->dept);?>"><?php echo zget($depts, $meeting->dept);?></td>
        <td class='c-project' title="<?php echo zget($projects, $meeting->project);?>"><?php echo zget($projects, $meeting->project);?></td>
        <td class='c-execution' title="<?php echo zget($executions, $meeting->execution);?>"><?php echo zget($executions, $meeting->execution, '');?></td>
        <td><?php echo $meeting->date;?></td>
        <td title="<?php echo zget($rooms, $meeting->room);?>"><?php echo zget($rooms, $meeting->room, '')?></td>
        <td><?php echo zget($users, $meeting->host);?></td>
        <td><?php echo zget($users, $meeting->minutedBy);?></td>
        <td class='c-actions'>
          <?php
          $params = "meetingID=$meeting->id";
          common::printIcon('meeting', 'edit', $params . "&from=$from", $meeting, "list");
          common::printIcon('meeting', 'minutes', $params, $meeting, "list", 'summary', '', 'iframe', true);
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . "common/view/footer.html.php"?>
