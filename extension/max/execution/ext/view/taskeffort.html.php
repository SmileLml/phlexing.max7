<?php
/**
 * The task group view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun <sunguangming@cnezsoft.com>
 * @package     execution
 * @version     $Id: taskeffort.html.php 7713 2019-03-27 09:41:06Z sunguangming@cnezsoft.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datatable.html.php';?>
<?php js::set('leftText', $lang->task->leftAB);?>
<?php js::set('consumedText', $lang->task->consumedAB);?>
<?php
$weekend    = $type == 'noweekend' ? 'withweekend' : 'noweekend';
$taskList   = array();
$groupIndex = 0;
$rowIndex   = 0;
?>

<div id="mainMenu" class="clearfix table-row">
  <div class="btn-toolbar pull-left">
    <?php if(common::hasPriv('execution', 'computeTaskEffort')) echo html::a('#computeModal', '<i class="icon icon-refresh"></i> ' . $lang->execution->computeTaskEffort, '', "title='{$lang->execution->computeTaskEffort}' class='btn btn-primary' data-toggle='modal'")?>
    <?php echo html::a($this->createLink('execution', 'taskeffort', "executionID=$executionID&group=$groupBy&type=$weekend"), $lang->execution->$weekend, '', "class='btn btn-link'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php
    $checkObject = new stdclass();
    $checkObject->execution = $executionID;
    $link = $this->createLink('task', 'create', "execution=$executionID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : ''));
    if(common::hasPriv('task', 'create', $checkObject)) echo html::a($link, "<i class='icon icon-plus'></i> {$lang->task->create}", '', "class='btn btn-primary'");
    ?>
  </div>
</div>
<div id='tasksTable' class='main-table load-indicator loading' data-ride='table' data-checkable='false' data-group='true' data-hot='true'>
  <?php if(empty($tasks)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->task->noTask;?></span>
      <?php if(common::hasPriv('task', 'create', $checkObject)):?>
      <?php echo html::a($this->createLink('task', 'create', "execution=$executionID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : '')), "<i class='icon icon-plus'></i> " . $lang->task->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <?php $executionBegin = helper::isZeroDate($execution->realBegan) ? $execution->begin : $execution->realBegan;?>
  <?php if((time() - strtotime($executionBegin)) < 0):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->execution->noStart;?></span>
    </p>
  </div>
  <?php else:?>
  <div id='tableContainer'>
    <div id='tableHeader' class='clearfix'>
      <div id='headerColGroup'>
        <div id='groupMenu' class="dropdown">
          <a href="" data-toggle="dropdown" class="btn text-left btn-block btn-info clearfix">
            <span class='pull-left'><?php echo zget($lang->execution->groups, $groupBy, null);?></span>
            <i class="icon icon-caret-down hl-primary text-primary pull-right"></i>
          </a>
          <ul class="dropdown-menu">
            <?php foreach($lang->execution->groups as $key => $value):?>
            <?php
            if(empty($key)) continue;
            if($execution->type == 'ops' && $key == 'story') continue;
            $active = $key == $groupBy ? "class='active'" : '';
            echo "<li $active>"; common::printLink('execution', 'taskeffort', "execution=$executionID&groupBy=$key", $value); echo '</li>';
            ?>
            <?php endforeach;?>
          </ul>
        </div>
      </div>
      <div id='headerColName'><?php echo $lang->task->common;?></div>
      <div id='headerTimeline'>
        <div id='timeline'>
          <div id='timeList'></div>
        </div>
      </div>
    </div>
    <div id='tableBody'>
      <div id='groups'>
        <?php $isGroupByAssignTo = $groupBy == 'assignedTo';?>
        <?php foreach($tasks as $groupKey => $groupTasks):?>
        <?php
          $groupName = $groupKey;
          if($groupBy == 'story') $groupName = empty($groupName) ? $this->lang->task->noStory : zget($groupByList, $groupKey);
          if($isGroupByAssignTo and $groupName == '') $groupName = $this->lang->task->noAssigned;
        ?>
        <div class='row-group' data-id='<?php echo $groupIndex; ?>' id='group-<?php echo $groupIndex; ?>'>
          <?php $index = 0;?>
          <?php foreach($groupTasks as $task):?>
            <?php $taskList[] = isset($task->burn) ? $task->burn : 0; ?>
            <?php if($index == 0): ?>
            <div class='cell-group group-name'>
              <span class='<?php echo ($isGroupByAssignTo and $groupName == $app->user->account) ? 'text-red' : 'text-primary'; ?>'><?php echo $groupName; ?></span>
              <?php if($isGroupByAssignTo and isset($members[$task->assignedTo])): ?>
              <div class='small'><?php printf($lang->execution->memberHoursAB, $users[$task->assignedTo], $members[$task->assignedTo]->totalHours); ?></div>
              <?php endif; ?>
            </div>
            <div class='cell-group group-tasks'>
            <?php endif; ?>
              <div class='group-task' id='task-<?php echo $rowIndex; ?>' data-row='<?php echo $rowIndex; ?>' data-group='<?php echo $groupIndex; ?>' data-index='<?php echo $index; ?>'>
                <?php
                  if(isset($task->multiple)) echo '<span class="label label-light label-badge">' . $lang->task->multipleAB . '</span> ';
                  if($task->parent > 0 && $task->isParent == '0') echo '<span class="label label-light label-badge">' . $lang->task->childrenAB . '</span> ';
                  if($task->isParent) echo '<span class="label">' . $lang->task->parentAB . '</span> ';
                  if(!common::printLink('task', 'view', "task=$task->id", $task->name, '', 'class="text-primary"')) echo $task->name;
                ?>
              </div>
              <?php $index++;?>
              <?php $rowIndex++;?>
              <?php endforeach;?>
            </div>
        </div>
        <?php $groupIndex++;?>
        <?php endforeach;?>
      </div>
      <div id='cellsContainer'>
        <div id='cells'></div>
      </div>
    </div>
    <div id='tableFooter'>
      <div id='footerTotal'><?php echo $lang->team->totalHours?></div>
      <div id='scrollbarContainer'>
        <div id='scrollbar'></div>
      </div>
      <div id='totalCells'>
        <div id='totalDays'></div>
      </div>
    </div>
  </div>
  <?php endif;?>
  <?php endif;?>
</div>
<?php js::set('counts', $counts); ?>
<?php js::set('rowsCount', $rowIndex); ?>
<?php js::set('groupsCount', $groupIndex); ?>
<?php js::set('days', $dateList); ?>
<?php js::set('taskList', $taskList); ?>
<?php js::set('today', helper::today()); ?>
<?php js::set('endMoreThanToday', $lang->execution->endMoreThanToday); ?>
<?php js::set('beginMoreThanEnd', $lang->execution->beginMoreThanEnd); ?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
<div class="modal fade" id="computeModal">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $lang->execution->computeTaskEffort;?></h4>
      </div>
      <div class="modal-body">
        <table class='table table-form'>
          <tr>
            <th id='dateRange'><?php echo $lang->execution->beginAndEnd;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('begin', date('Y-m-d', strtotime('-30 days')), "class='form-control form-date'");?>
                <span class='input-group-addon'><?php echo $lang->execution->to;?></span>
                <?php echo html::input('end', date('Y-m-d'), "class='form-control form-date'");?>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan='2' class='text-center'>
              <?php echo html::commonButton($lang->save, "id='saveButton'", 'btn btn-primary btn-wide');?>
              <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-default btn-wide');?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
