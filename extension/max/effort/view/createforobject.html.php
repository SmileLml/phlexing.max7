<?php
/**
 * The create view of effort module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     effort
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php if($objectType == 'task' and !$this->task->canOperateEffort($task) and empty($myOrders)):?>
<style>#mainContent {min-height: unset;}</style>
<?php endif;?>
<?php if($objectType == 'task'):?>
<?php js::set('taskID', $objectID);?>
<?php js::set('foldEffort', $lang->task->foldEffort);?>
<?php js::set('unfoldEffort', $lang->task->unfoldEffort);?>
<?php endif;?>
<?php js::set('noticeSaveRecord', $this->lang->effort->noticeSaveRecord);?>
<?php js::set('objectType', $objectType);?>
<?php js::set('objectID', $objectID);?>
<?php js::set('today', helper::today());?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class="label label-id"><?php echo $objectID;?></span>
      <span title='<?php echo $modalTitle;?>'><?php echo $modalTitle;?></span>
    </h2>
    <?php if($objectType == 'task'):?>
    <ul class='nav nav-default hours'>
      <li><span><?php echo $lang->task->estimate;?></span> </li>
      <li><span class='estimateTotally'><?php echo $task->estimate . 'h';?></span></li>
      <li>，</li>
      <li><span><?php echo $lang->task->consumed;?></span> </li>
      <li><span class='consumedTotally'><?php echo $task->consumed . 'h';?></span></li>
    </ul>
    <?php endif;?>
  </div>
  <?php if($efforts):?>
  <?php if($objectType == 'task' and !empty($task->team) and $task->mode == 'linear'):?>
  <?php include $this->app->getModuleRoot() . 'task/view/lineareffort.html.php';?>
  <?php else:?>
  <?php $tableClass = $objectType == 'task' ? 'taskEffort' : '';?>
  <table class="table table-bordered table-fixed table-recorded has-sort-head <?php echo $tableClass;?>" id='objectTable' style='margin-bottom:10px;'>
    <thead>
      <tr>
        <?php if($objectType == 'task'):?>
        <?php $vars = "objectType=$objectType&taskID=$task->id&from=$from&orderBy=%s";?>
        <th class="w-120px"><?php common::printOrderLink('date', !strpos($orderBy, ',') ? $orderBy : 'date_asc', $vars, $lang->task->date);?></th>
        <?php else:?>
        <th class="w-120px"><?php echo $lang->effort->date;?></th>
        <?php endif;?>
        <th class='w-120px'><?php echo $lang->effort->account;?></th>
        <th><?php echo $lang->effort->work;?></th>
        <th class='thWidth'><?php echo $lang->effort->consumed;?></th>
        <?php if($objectType == 'task'):?>
        <th class='thWidth'><?php echo $lang->effort->left;?></th>
        <?php endif;?>
        <th class='w-80px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1;?>
      <?php foreach($efforts as $effort):?>
      <?php $hidden = ($objectType == 'task' and $taskEffortFold and $i > 3) ? 'hidden' : ''?>
      <?php
      $canOperateEffort = $objectType == 'task' ? $this->task->canOperateEffort($task, $effort) : $this->loadModel('common')->canOperateEffort($effort);
      ?>
      <tr class="<?php echo $hidden;?>">
        <td class='text-center'><?php echo $effort->date?></td>
        <td class='text-center'><?php echo zget($users, $effort->account);?></td>
        <td title='<?php echo $effort->work;?>'><?php echo $effort->work;?></td>
        <td class='text-center' title="<?php echo $effort->consumed . ' ' . $lang->effort->workHour;?>"><?php echo $effort->consumed . ' h'?></td>
        <?php if($objectType == 'task'):?>
        <td class='text-center' title="<?php echo $effort->left . ' ' . $lang->effort->workHour;?>"><?php echo $effort->left . ' h';?></td>
        <?php endif;?>
        <td class='text-center c-actions'>
          <?php
          $tipsLang    = (!empty($effort->project) || !empty($effort->execution)) ? $lang->task->effortOperateTips : $lang->effort->operateTips;
          $operateTips = (!$canOperateEffort && (($objectType == 'task' && empty($taks->team)) || $objectType != 'task')) ? $tipsLang : '';
          common::printIcon('effort', 'edit', "effortID=$effort->id", '', 'list', 'edit', '', 'showinonlybody', true, $canOperateEffort ? '' : 'disabled', $operateTips ? sprintf($operateTips, $lang->task->update) : '');
          common::printIcon('effort', 'delete', "effortID=$effort->id", '', 'list', 'trash', 'hiddenwin', 'showinonlybody', false, $canOperateEffort ? '' : 'disabled', $operateTips ? sprintf($operateTips, $lang->delete) : '');
          ?>
        </td>
      </tr>
      <?php $i ++;?>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php if($objectType == 'task' and count($efforts) > 3):?>
  <div id='toggleFoldIcon'>
    <?php $icon     = $taskEffortFold ? 'icon-angle-down' : 'icon-angle-top'?>
    <?php $iconText = $taskEffortFold ? $lang->task->unfoldEffort : $lang->task->foldEffort;?>
    <span class='icon-border'><i class="icon <?php echo $icon;?>"></i></span>
    <span class='text'><?php echo $iconText;?></span>
  </div>
  <?php endif;?>
  <?php endif;?>
  <?php endif;?>
  <?php if($objectType == 'task' and !$this->task->canOperateEffort($task) and empty($myOrders)):?>
  <div class="alert with-icon">
    <i class="icon-exclamation-sign icon-rotate-180"></i>
    <div class="content">
      <?php if(!isset($task->members[$app->user->account])):?>
      <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $lang->task->teamMember . '</strong>', $lang->task->logEfforts);?></p>
      <?php elseif($task->assignedTo != $app->user->account and $task->mode == 'linear'):?>
      <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $task->assignedToRealName . '</strong>', $lang->task->logEfforts);?></p>
      <?php endif;?>
    </div>
  </div>
  <?php else:?>
  <form method='post' target='hiddenwin' id='createEffort' class='hidden'>
    <?php
    $readonly = '';
    $left     = '';
    if($objectType == 'task' and ($task->assignedTo != $app->user->account or strpos('closed,cancel,done,pause', $task->status) !== false) and !empty($task->team) and $task->mode == 'linear' and !empty($myOrders))
    {
        $readonly      = ' readonly';
        $left          = 0;
        $reverseOrders = array_reverse($myOrders, true);
        foreach($reverseOrders as $order => $count) $reverseOrders[$order] = $order + 1;
    }
    ?>
    <?php $thClass = $objectType == 'task' ? 'required' : '';?>
    <table class='table table-form table-fixed table-record'>
      <thead class='text-center'>
        <tr>
          <th class='w-30px'><?php echo $lang->idAB;?></th>
          <th class="w-150px <?php echo $thClass;?>"><?php echo $lang->effort->date;?></th>
          <?php if($readonly):?>
          <th class="w-60px <?php if(count($reverseOrders) == 1) echo "hidden"?>"><?php echo $lang->task->teamOrder;?></th>
          <?php endif;?>
          <th class="<?php echo $thClass;?>"><?php echo $lang->effort->work;?></th>
          <th class="thWidth <?php echo $thClass;?>"><?php echo $lang->effort->consumed;?></th>
          <?php if($objectType == 'task'):?>
          <th class="thWidth <?php if(empty($readonly)) echo 'required'?>"><?php echo $lang->effort->left;?></th>
          <?php endif;?>
        </tr>
      </thead>
      <tbody>
        <?php $today = date(DT_DATE1);?>
        <?php for($i = 1; $i <= 5; $i++):?>
        <tr class='text-top'>
          <td class='text-center'><?php echo $i . html::hidden("id[$i]", $i);?></td>
          <td class='text-center'>
            <div class='input-group date-group'>
              <?php echo html::input("dates[$i]", $today, "class='form-control form-date'");?>
              <span class='input-group-addon'><i class='icon icon-calendar'></i></span>
            </div>
          </td>
          <?php if($readonly):?>
          <td class='<?php if(count($reverseOrders) == 1) echo "hidden"?>'><?php echo html::select("order[$i]", $reverseOrders, '', "class='form-control'")?></td>
          <?php endif;?>
          <td>
            <?php
            echo html::hidden("objectType[$i]", $objectType);
            echo html::hidden("objectID[$i]", $objectID);
            echo html::textarea("work[$i]", '', "class='form-control' rows=1");
            ?>
          </td>
          <td class='text-center'>
            <div class='input-group'>
              <?php echo html::input("consumed[$i]", '', "class='form-control text-center' autocomplete='off'");?>
              <span class='input-group-addon'>h</span>
            </div>
          </td>
          <?php if($objectType == 'task'):?>
          <td class='text-center'>
            <div class='input-group'>
              <?php echo html::input("left[$i]", $left, "class='form-control text-center' autocomplete='off' {$readonly}");?>
              <span class='input-group-addon'>h</span>
            </div>
          </td>
          <?php endif;?>
        </tr>
        <?php endfor;?>
      </tbody>
    </table>
    <div class='table-footer text-center form-actions'>
      <?php
      if($objectType == 'task')
      {
          $noticeFinish = $lang->effort->noticeFinish;
          if(!empty($task->team) and $task->mode == 'linear')
          {
              $nextAccount = '';
              $isCurrent   = false;
              foreach($task->team as $taskTeam)
              {
                  if($isCurrent)
                  {
                      $nextAccount = $taskTeam->account;
                      break;
                  }
                  if($task->assignedTo == $taskTeam->account and $taskTeam->account == $app->user->account and $taskTeam->status != 'done') $isCurrent = true;
              }
              if($nextAccount) $noticeFinish = sprintf($lang->task->confirmTransfer, zget($users, $nextAccount));
          }
          js::set('noticeFinish', $noticeFinish);
      }
      ?>
      <?php echo html::submitButton();?>
      <?php echo html::backButton();?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php if($objectType == 'task' and count($efforts) > 3):?>
<style>.taskEffort {margin-bottom: 5px !important;}</style>
<?php endif;?>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php'?>
