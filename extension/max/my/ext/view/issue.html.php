<?php
/**
 * The issue view file of my module of ZenTaoPMS.
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
<?php js::set('mode', $mode);?>
<?php js::set('todoCount', $todoCount);?>
<?php js::set('isMax', $isMax);?>
<?php js::set('isBiz', $isBiz);?>
<?php js::set('isOpenedURAndSR', $isOpenedURAndSR);?>
<style>
.c-id {width: 50px;}
.c-type, .c-owner, .c-severity {width: 80px;}
.c-createdDate{width: 100px;}
.c-assignedTo {padding-left: 29px !important;}
.c-actions-8 {width: 240px !important;}
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
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->user->search;?></a>
  </div>
</div>
<div id="mainContent">
  <div class="cell<?php if($type == 'bySearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=issue');?>" data-module='issue'></div>
  <?php if(empty($issues)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
  </div>
  <?php else:?>
  <form id='myTaskForm' class="main-table table-issue" data-ride="table" method="post">
    <table class="table has-sort-head table-fixed" id='issuetable'>
      <?php $vars = "mode=$mode&type=$type&param=$param&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
      <thead>
        <tr>
          <th class="c-id"><?php echo common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
          <th class="c-name"><?php echo common::printOrderLink('title', $orderBy, $vars, $lang->issue->title);?></th>
          <th><?php echo common::printOrderLink('project', $orderBy, $vars, $lang->issue->project);?></th>
          <th class="c-pri text-center"><?php echo common::printOrderLink('pri', $orderBy, $vars, $lang->issue->priAB);?></th>
          <th class="c-severity"><?php echo common::printOrderLink('severity', $orderBy, $vars, $lang->issue->severity);?></th>
          <th class="c-type"><?php echo common::printOrderLink('type', $orderBy, $vars, $lang->issue->type);?></th>
          <th class="c-owner"><?php echo common::printOrderLink('owner', $orderBy, $vars, $lang->issue->owner);?></th>
          <th class="c-createdDate"><?php echo common::printOrderLink('createdDate', $orderBy, $vars, $lang->issue->createdDate);?></th>
          <?php if($type == 'assignedTo'):?>
          <th class="c-assignedTo"><?php echo common::printOrderLink('assignedBy', $orderBy, $vars, $lang->issue->assignedBy);?></th>
          <?php else:?>
          <th class="c-assignedTo text-center"><?php echo common::printOrderLink('assignedTo', $orderBy, $vars, $lang->issue->assignedTo);?></th>
          <?php endif;?>
          <th class="c-status"><?php echo common::printOrderLink('status', $orderBy, $vars, $lang->issue->status);?></th>
          <th class="c-actions-8 text-center"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $showDividing = ((common::hasPriv('issue', 'confirm') or common::hasPriv('issue', 'resolve') or common::hasPriv('issue', 'close') or common::hasPriv('issue', 'cancel')) and (common::hasPriv('issue', 'activate') or common::hasPriv('effort', 'createForObject') or common::hasPriv('issue', 'edit')));?>
        <?php foreach($issues as $issue):?>
        <tr>
          <td class="c-id"><?php printf('%03d', $issue->id);?></td>
          <td class="text-ellipsis" title="<?php echo $issue->title;?>"><?php echo html::a($this->createLink('issue', 'view', "id=$issue->id", '', '', $issue->project), $issue->title, '', "data-group='project'");?></td>
          <td title="<?php echo zget($projectList, $issue->project, '');?>"><?php echo zget($projectList, $issue->project, '');?></td>
          <td class="c-pri text-center" title="<?php echo $issue->pri;?>"><span class='label-pri <?php echo 'label-pri-' . $issue->pri;?>'><?php echo $issue->pri;?></span></td>
          <td class='severity-issue severity-<?php echo $issue->severity;?>' title="<?php echo zget($lang->issue->severityList, $issue->severity);?>"><?php echo zget($lang->issue->severityList, $issue->severity);?></td>
          <td title="<?php echo zget($lang->issue->typeList, $issue->type);?>"><?php echo zget($lang->issue->typeList, $issue->type);?></td>
          <td title="<?php echo zget($users, $issue->owner);?>"><?php echo zget($users, $issue->owner);?></td>
          <?php $issue->createdDate = substr($issue->createdDate, 0, 10)?>
          <td title="<?php echo $issue->createdDate;?>"><?php echo $issue->createdDate;?></td>
          <?php
          $assignedClass = '';
          if($issue->assignedTo == $app->user->account) $assignedClass = 'assigned-current';
          if(!empty($issue->assignedTo) and $issue->assignedTo != $app->user->account) $assignedClass = 'assigned-other';
          ?>
          <?php if($type == 'assignedTo'):?>
          <td title="<?php echo zget($users, $issue->assignedBy);?>" class="c-assignedTo"><?php echo zget($users, $issue->assignedBy);?></td>
          <?php else:?>
          <td  class="c-assignedTo <?php echo $assignedClass;?>"><?php echo $this->issue->printAssignedHtml($issue, $users);?></td>
          <?php endif;?>
          <td class='status-issue status-<?php echo $issue->status;?>' title="<?php echo zget($lang->issue->statusList, $issue->status);?>"><?php echo zget($lang->issue->statusList, $issue->status);?></td>
          <td class="c-actions">
            <?php
              $params = "issueID=$issue->id";
              echo common::printIcon('issue', 'confirm', $params, $issue, 'list', 'ok', '', 'iframe', 'yes', '', $lang->issue->confirm);
              echo common::printIcon('issue', 'resolve', $params, $issue, 'list', 'checked', '', 'iframe', 'yes', '', $lang->issue->resolve);
              echo common::printIcon('issue', 'close', $params, $issue, 'list', 'off', '', 'iframe', 'yes');
              echo common::printIcon('issue', 'cancel', $params, $issue, 'list', 'ban-circle', '', 'iframe', 'yes');
              echo common::printIcon('issue', 'activate', $params, $issue, 'list', 'magic', '', 'iframe', 'yes', '', $lang->issue->activate);
              if($showDividing) echo '<div class="dividing-line"></div>';
              echo common::printIcon('effort', 'createForObject', "objectType=issue&objectID=$issue->id", '', 'list', 'time', '', 'iframe', true, '', $lang->issue->effort);
              echo common::printIcon('issue', 'edit', $params, $issue, 'list', 'edit', '', 'iframe', 'yes');
              $deleteClass = common::hasPriv('issue', 'delete') ? 'btn' : 'btn disabled';
              echo html::a($this->createLink('issue', 'delete', $params), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->issue->delete}' class='$deleteClass'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
