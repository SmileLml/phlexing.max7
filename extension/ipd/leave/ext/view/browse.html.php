<?php
/**
 * The browse view file of leave module of Ranzhi.
 *
 * @copyright   Copyright 2009-2018 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     leave
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
  <style>
  #menuActions{float:right !important; margin-top: -60px !important;}
  .input-group-required > .required::after, .required-wrapper.required::after {top:12px !important;}
  .modal-body .table {margin-bottom:0px !important;}
  </style>
  <div id='featurebar'>
    <ul class='nav'>
    <?php
    $methodName = strtolower($this->app->rawMethod);
    foreach($lang->leave->featureBar['personal'] as $method => $name)
    {
        $class = strtolower($method) == $methodName ? "class='active'" : '';
        if(common::hasPriv('leave', $method)) echo "<li id='$method' $class>" . html::a($this->createLink('leave', $method), $name) . '</li>';
    }
    ?>
    </ul>
  </div>

<?php js::set('confirmReview', $lang->leave->confirmReview)?>
<div id='menuActions'>
  <?php extCommonModel::printLink('oa.leave', 'export', "mode=all&orderBy={$orderBy}", $lang->exportIcon . $lang->export, "class='btn btn-primary iframe' data-width='700'");?>
  <?php extCommonModel::printLink('oa.leave', 'create', '', "<i class='icon icon-plus'></i> {$lang->leave->create}", "data-toggle='modal' class='btn btn-primary'")?>
</div>
<?php if($type != 'browseReview'):?>
<div class='with-side'>
  <div class='side'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-ride='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php extCommonModel::printLink('oa.leave', $type, "date=$year", $year);?>
            <ul>
              <?php foreach($monthList[$year] as $month):?>
              <li class='<?php echo ($year == $currentYear and $month == $currentMonth) ? 'active' : ''?>'>
                <?php extCommonModel::printLink('oa.leave', $type, "date=$year$month", $year . $month);?>
              </li>
              <?php endforeach;?>
            </ul>
          </li>
          <?php endforeach;?>
        </ul>
      </div>
    </div>
  </div>
  <div class='main'>
<?php endif;?>
<?php $batchReview = $type == 'browseReview' && commonModel::hasPriv('leave', 'batchReview');?>
    <div class='panel'>
      <?php if(empty($leaveList)):?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->noData;?></span>
          <?php if(common::hasPriv('leave', 'create')):?>
          <?php echo html::a($this->createLink('leave', 'create'), "<i class='icon icon-plus'></i> " . $lang->leave->create, '', "class='btn btn-info' data-toggle='modal'");?>
          <?php endif;?>
        </p>
      </div>
      <?php else:?>
      <?php if($batchReview):?>
      <form id='batchReviewForm' method='post' action='<?php echo inlink('batchReview', 'status=pass');?>'>
      <?php endif;?>
      <table class='table table-hover text-center table-fixed tablesorter' id='leaveTable'>
        <thead>
          <tr class='text-center'>
            <?php $vars = "&date={$date}&orderBy=%s";?>
            <th class='w-50px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->leave->id, 'leave', $type);?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->leave->createdBy, 'leave', $type);?></th>
            <th class='w-100px visible-lg'><?php echo $lang->user->dept;?></th>
            <th class='w-60px'><?php commonModel::printOrderLink('type', $orderBy, $vars, $lang->leave->type, 'leave', $type);?></th>
            <th class='w-140px'><?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->leave->start, 'leave', $type);?></th>
            <th class='w-140px'><?php commonModel::printOrderLink('end', $orderBy, $vars, $lang->leave->finish, 'leave', $type);?></th>
            <th class='w-140px'><?php commonModel::printOrderLink('backDate', $orderBy, $vars, $lang->leave->backDate, 'leave', $type);?></th>
            <th class='w-70px visible-lg'><?php commonModel::printOrderLink('hours', $orderBy, $vars, $lang->leave->hours, 'leave', $type);?></th>
            <th class='text-left'><?php echo $lang->leave->desc;?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->leave->status, 'leave', $type);?></th>
            <?php if($type == 'personal'):?>
            <?php $class = $this->app->clientLang == 'en' ? 'w-200px' : 'w-180px';?>
            <th class='<?php echo $class;?>'><?php echo $lang->actions;?></th>
            <?php else:?>
            <?php $class = $this->app->clientLang == 'en' ? 'w-160px' : 'w-130px';?>
            <th class='<?php echo $class;?>'><?php echo $lang->actions;?></th>
            <?php endif;?>
          </tr>
        </thead>
        <?php foreach($leaveList as $leave):?>
        <?php $viewUrl = commonModel::hasPriv('leave', 'view') ? $this->createLink('leave', 'view', "id={$leave->id}&type=$type", '', true) : '';?>
        <?php if($type == 'browseReview' && $leave->type == 'annual' && isset($leftAnnualDays[$leave->createdBy])):?>
        <tr id='leave<?php echo $leave->id;?>' >
        <?php else:?>
        <tr id='leave<?php echo $leave->id;?>' >
        <?php endif?>
          <td class='idTD'>
            <?php if($batchReview):?>
            <label class='checkbox-inline'><input type='checkbox' name='leaveIDList[]' value='<?php echo $leave->id;?>'/> <?php echo $leave->id;?></label>
            <?php else:?>
            <?php echo $leave->id;?>
            <?php endif;?>
          </td>
          <td><?php echo zget($users, $leave->createdBy);?></td>
          <td class='visible-lg'><?php echo zget($deptList, $leave->dept);?></td>
          <td><?php echo zget($lang->leave->typeList, $leave->type);?></td>
          <td><?php echo formatTime($leave->begin . ' ' . $leave->start, DT_DATETIME2);?></td>
          <td><?php echo formatTime($leave->end . ' ' . $leave->finish, DT_DATETIME2);?></td>
          <td><?php echo formatTime($leave->backDate, DT_DATETIME2);?></td>
          <td class='visible-lg'><?php echo $leave->hours == 0 ? '' : $leave->hours;?></td>
          <td class='text-left' title='<?php echo $leave->desc;?>'><?php echo $leave->desc;?></td>
          <td class='leave-<?php echo $leave->status?>' title='<?php echo $leave->statusLabel;?>'><?php echo $leave->statusLabel;?></td>
          <td class='actionTD text-left'>
            <?php
            if($viewUrl) echo baseHTML::a($viewUrl, $lang->detail, "data-toggle='modal'");
            if($type == 'personal')
            {
                if($leave->status == 'pass' and date('Y-m-d H:i:s') > "$leave->begin $leave->start" && date('Y-m-d H:i:s') < "$leave->end $leave->finish" && $leave->backDate != "$leave->end $leave->finish")
                {
                    extCommonModel::printLink('oa.leave', 'back', "id={$leave->id}", $lang->leave->back, "data-toggle='modal'");
                }
                else
                {
                    echo baseHTML::a('###', $lang->leave->back, "disabled='disabled'");
                }
                $switchLabel = $leave->status == 'wait' ? $lang->leave->cancel : $lang->leave->commit;
                if(strpos(',wait,draft,', ",$leave->status,") !== false)
                {
                    extCommonModel::printLink('oa.leave', 'switchstatus', "id={$leave->id}", $switchLabel, "class='reload'");
                }
                else
                {
                    echo baseHTML::a('###', $switchLabel,  "disabled='disabled'");
                }
                if(strpos(',wait,draft,reject,', ",$leave->status,") !== false)
                {
                    extCommonModel::printLink('oa.leave', 'edit',   "id={$leave->id}", $lang->edit,   "data-toggle='modal'");
                    extCommonModel::printLink('oa.leave', 'delete', "id={$leave->id}", $lang->delete, "class='deleter'");
                }
                else
                {
                    echo baseHTML::a('###', $lang->edit,   "disabled='disabled'");
                    echo baseHTML::a('###', $lang->delete, "disabled='disabled'");
                }
            }
            else
            {
              $canReview = $this->leave->isClickable($leave, 'review');

              if($canReview)
              {
                  $params = $leave->status == 'back' ? '&mode=back' : '';
                  echo baseHTML::a(inlink('review', "id={$leave->id}&status=pass$params"),   $lang->leave->reviewStatusList['pass'],   "class='reviewPass'");
                  echo baseHTML::a(inlink('review', "id={$leave->id}&status=reject$params"), $lang->leave->reviewStatusList['reject'], "data-toggle='modal'");
              }
              else
              {
                  echo baseHTML::a('javascript:;', $lang->leave->reviewStatusList['pass'],   "class='disabled'");
                  echo baseHTML::a('javascript:;', $lang->leave->reviewStatusList['reject'], "class='disabled'");
              }
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
      <?php endif;?>
    </div>
<?php if($type != 'browseReview'):?>
  </div>
</div>
<?php endif;?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
