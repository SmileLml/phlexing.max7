<?php
/**
 * The view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: view.html.php 4808 2013-06-17 05:48:13Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php include $app->getModuleRoot() . 'ai/view/promptmenu.html.php';?>
<?php $browseLink = $this->session->taskList ? $this->session->taskList : $this->createLink('marketresearch', 'stage', "researchID=$task->project");?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $task->id?></span>
      <span class="text" title='<?php echo $task->name;?>' style='color: <?php echo $task->color; ?>'>
        <?php if($task->parent > 0) echo '<span class="label label-badge label-primary no-margin">' . $lang->task->childrenAB . '</span>';?>
        <?php if($task->parent > 0) echo isset($task->parentName) ? html::a(inlink('view', "taskID={$task->parent}"), $task->parentName) . ' / ' : '';?><?php echo $task->name;?>
      </span>
      <?php if($task->deleted):?>
      <span class='label label-danger'><?php echo $lang->task->deleted;?></span>
      <?php endif;?>
    </div>
  </div>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
<div id="scrollContent">
<?php endif;?>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->task->legendDesc;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($task->desc) ? $task->desc : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
      <?php if(!empty($task->children)):?>
      <div class='detail'>
        <div class='detail-title'><?php echo $this->lang->task->children;?></div>
        <div class='detail-content article-content'>
          <table class='table table-hover table-fixed' id='childrenTable'>
            <thead>
              <tr class='text-center'>
                <th class='c-id'> <?php echo $lang->task->id;?></th>
                <th class='c-lblPri'> <?php echo $lang->task->lblPri;?></th>
                <th>                <?php echo $lang->task->name;?></th>
                <th class='c-deadline'><?php echo $lang->task->deadline;?></th>
                <th class='c-assignedTo'> <?php echo $lang->task->assignedTo;?></th>
                <th class='c-status'> <?php echo $lang->task->status;?></th>
                <th class='visible-lg c-consumedAB'><?php echo $lang->task->consumedAB . $lang->task->lblHour;?></th>
                <th class='visible-lg c-leftAB'><?php echo $lang->task->leftAB . $lang->task->lblHour;?></th>
                <th class='c-actions'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($task->children as $child):?>
              <tr class='text-center'>
                <td><?php echo $child->id;?></td>
                <td><?php if($child->pri) echo "<span class='label-pri label-pri-" . $child->pri . "'>" . zget($this->lang->task->priList, $child->pri, $child->pri) . "</span>";?></td>
                <td class='text-left' title='<?php echo $child->name;?>'><a class="iframe" data-width="90%" href="<?php echo $this->createLink('marketresearch', 'viewtask', "taskID=$child->id", '', true); ?>"><?php echo $child->name;?></a></td>
                <td><?php echo $child->deadline;?></td>
                <td id='assignedTo'><?php echo $this->marketresearch->getAssignToHtml($child, $users);?></td>
                <td><?php echo $this->processStatus('task', $child);?></td>
                <td class='visible-lg'><?php echo $child->consumed;?></td>
                <td class='visible-lg'><?php echo $child->left;?></td>
                <td class='c-actions'>
                  <?php
                  common::printIcon('marketresearch', 'starttask', "taskID=$child->id", $child, 'list', 'play', '', 'iframe showinonlybody', true);
                  common::printIcon('marketresearch', 'finishtask', "taskID=$child->id", $child, 'list', 'checked', '', 'iframe showinonlybody', true);
                  common::printIcon('marketresearch', 'closetask', "taskID=$child->id", $child, 'list', 'off', '', 'iframe showinonlybody', true);
                  common::printIcon('marketresearch', 'recordEstimate', "taskID=$child->id", $child, 'list', 'time', '', 'iframe showinonlybody', true);
                  common::printIcon('marketresearch', 'edittask', "taskID=$child->id", $child, 'list', 'edit');
                  common::printIcon('marketresearch', 'activatetask', "taskID=$child->id", $child, 'list', 'magic', '', 'iframe showinonlybody', true);
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endif;?>
      <?php
      echo $this->fetch('file', 'printFiles', array('files' => $task->files, 'fieldset' => 'true', 'object' => $task, 'method' => 'view', 'showDelete' => false));

      $canBeChanged = common::canBeChanged('task', $task);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=task&objectID=$task->id");
      ?>
    </div>
    <?php if($this->app->getViewType() != 'xhtml'):?>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <?php endif;?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php if(!isonlybody() and $this->app->getViewType() != 'xhtml'):?>
        <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn'");?>
        <?php echo "<div class='divider'></div>";?>
        <?php endif;?>
        <?php $task->executionList = $execution;?>
        <?php echo $this->marketresearch->buildResearchTaskViewMenu($task);?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendBasic' data-toggle='tab'><?php echo $lang->task->legendBasic;?></a></li>
          <li><a href='#legendLife' data-toggle='tab'><?php echo $lang->task->legendLife;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendBasic'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->marketresearch->execution;?></th>
                  <td>
                    <?php
                        echo $execution->name;
                    ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->task->assignedTo;?></th>
                  <td>
                    <?php echo $task->assignedTo ? zget($users, $task->assignedTo) . $lang->at . substr($task->assignedDate, 0, 19) : $lang->noData;?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->task->status;?></th>
                  <td><span class='status-task status-<?php echo $task->status;?>'><span class="label label-dot"></span> <?php echo $this->processStatus('task', $task);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->task->progress;?></th>
                  <td><?php echo $task->progress . '%';?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->task->pri;?></th>
                  <td><span class='label-pri <?php echo 'label-pri-' . $task->pri;?>' title='<?php echo zget($lang->task->priList, $task->pri);?>'><?php echo $task->pri == '0' ? $lang->noData : zget($lang->task->priList, $task->pri)?></span></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='legendLife'>
            <table class='table table-data'>
              <tr>
                <th class='thWidth'><?php echo $lang->task->openedBy;?></th>
                <td><?php echo $task->openedBy ? zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate : $lang->noData;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->finishedBy;?></th>
                <td><?php echo ($task->finishedBy) ? zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate : $lang->noData;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->canceledBy;?></th>
                <td><?php echo $task->canceledBy ? zget($users, $task->canceledBy, $task->canceledBy) . $lang->at . $task->canceledDate : $lang->noData;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->closedBy;?></th>
                <td><?php echo $task->closedBy ? zget($users, $task->closedBy, $task->closedBy) . $lang->at . $task->closedDate : $lang->noData;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->closedReason;?></th>
                <td><?php echo $task->closedReason ? $lang->task->reasonList[$task->closedReason] : $lang->noData;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->lastEdited;?></th>
                <td><?php echo $task->lastEditedBy ? zget($users, $task->lastEditedBy, $task->lastEditedBy) . $lang->at . $task->lastEditedDate : $lang->noData;?></td>
              </tr>
            </table>
          </div>
          <div class='tab-pane' id='legendTeam'>
            <table class='table table-data'>
              <thead>
              <tr>
                <th><?php echo $lang->task->team?></th>
                <th class='text-center c-hours'><?php echo $lang->task->estimateAB?></th>
                <th class='text-center c-hours'><?php echo $lang->task->consumedAB?></th>
                <th class='text-center c-hours'><?php echo $lang->task->leftAB?></th>
                <th class='text-center'><?php echo $lang->statusAB;?></th>
              </tr>
              </thead>
                <?php foreach($task->team as $member):?>
                <tr class='text-center'>
                  <td class='text-left'><?php echo zget($users, $member->account);?></td>
                  <td><?php echo (float)$member->estimate?></td>
                  <td><?php echo (float)$member->consumed?></td>
                  <td><?php echo (float)$member->left?></td>
                  <td class="status-<?php echo $member->status;?>"><?php echo zget($lang->task->statusList, $member->status);?></td>
                </tr>
                <?php endforeach;?>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#legendEffort' data-toggle='tab'><?php echo $lang->task->legendEffort;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendEffort'>
            <table class="table table-data">
              <tr>
                <th class='effortThWidth'><?php echo $lang->task->estimate;?></th>
                <td><?php echo $task->estimate . $lang->workingHour;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->consumed;?></th>
                <td><?php echo round($task->consumed, 2) . $lang->workingHour;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->left;?></th>
                <td><?php echo $task->left . $lang->workingHour;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->estStarted;?></th>
                <td><?php echo $task->estStarted;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->task->realStarted;?></th>
                <td><?php echo helper::isZeroDate($task->realStarted) ? '' : substr($task->realStarted, 0, 19); ?> </td>
              </tr>
              <tr>
                <th><?php echo $lang->task->deadline;?></th>
                <td>
                  <?php
                  echo $task->deadline;
                  if(isset($task->delay)) printf($lang->task->delayWarning, $task->delay);
                  ?>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
</div>
<?php endif;?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
