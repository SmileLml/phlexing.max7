<?php
/**
 * The browse view file of overtime module of Ranzhi.
 *
 * @copyright   Copyright 2009-2018 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     overtime
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
    foreach($lang->overtime->featureBar['personal'] as $method => $name)
    {
        $class = strtolower($method) == $methodName ? "class='active'" : '';
        if(common::hasPriv('overtime', $method)) echo "<li id='$method' $class>" . html::a($this->createLink('overtime', $method), $name) . '</li>';
    }
    ?>
    </ul>
  </div>

<?php js::set('confirmReview', $lang->overtime->confirmReview)?>
<div id='menuActions'>
  <?php extCommonModel::printLink('oa.overtime', 'export', "mode=all&orderBy={$orderBy}", $lang->exportIcon . $lang->export, "class='btn btn-primary iframe' data-width='700'");?>
  <?php extCommonModel::printLink('oa.overtime', 'create', "", "<i class='icon icon-plus'></i> {$lang->overtime->create}", "data-toggle='modal' class='btn btn-primary'")?>
</div>
<?php if($type != 'browseReview'):?>
<div class='with-side'>
  <div class='side'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-ride='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php extCommonModel::printLink('oa.overtime', $type, "date=$year", $year);?>
            <ul>
              <?php foreach($monthList[$year] as $month):?>
              <li class='<?php echo ($year == $currentYear and $month == $currentMonth) ? 'active' : ''?>'>
                <?php extCommonModel::printLink('oa.overtime', $type, "date=$year$month", $year . $month);?>
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
<?php $batchReview = $type == 'browseReview' && commonModel::hasPriv('overtime', 'batchReview');?>
    <div class='panel'>
      <?php if(empty($overtimeList)):?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->noData;?></span>
          <?php if(common::hasPriv('overtime', 'create')):?>
          <?php echo html::a($this->createLink('overtime', 'create'), "<i class='icon icon-plus'></i> " . $lang->overtime->create, '', "class='btn btn-info' data-toggle='modal'");?>
          <?php endif;?>
        </p>
      </div>
      <?php else:?>
      <?php if($batchReview):?>
      <form id='batchReviewForm' method='post' action='<?php echo inlink('batchReview', 'status=pass');?>'>
      <?php endif;?>
      <table class='table table-hover text-center table-fixed tablesorter' id='overtimeTable'>
        <thead>
          <tr class='text-center'>
            <?php $vars = "&date={$date}&orderBy=%s";?>
            <th class='w-80px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->overtime->id, 'overtime', $type);?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->overtime->createdBy, 'overtime', $type);?></th>
            <th class='w-100px visible-lg'><?php echo $lang->user->dept;?></th>
            <th class='w-80px'><?php commonModel::printOrderLink('type', $orderBy, $vars, $lang->overtime->type, 'overtime', $type);?></th>
            <th class='w-150px'><?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->overtime->begin, 'overtime', $type);?></th>
            <th class='w-150px'><?php commonModel::printOrderLink('end', $orderBy, $vars, $lang->overtime->end, 'overtime', $type);?></th>
            <th class='w-70px visible-lg'><?php commonModel::printOrderLink('hours', $orderBy, $vars, $lang->overtime->hours, 'overtime', $type);?></th>
            <th class='text-left'><?php echo $lang->overtime->desc;?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->overtime->status, 'overtime', $type);?></th>
            <?php if($type == 'personal'):?>
            <?php $class = $this->app->clientLang == 'en' ? 'w-180px' : 'w-140px';?>
            <th class='<?php echo $class;?>'><?php echo $lang->actions;?></th>
            <?php else:?>
            <?php $class = $this->app->clientLang == 'en' ? 'w-130px' : 'w-110px';?>
            <th class='<?php echo $class;?>'><?php echo $lang->actions;?></th>
            <?php endif;?>
          </tr>
        </thead>
        <?php foreach($overtimeList as $overtime):?>
        <?php $viewUrl = commonModel::hasPriv('overtime', 'view') ? $this->createLink('overtime', 'view', "id=$overtime->id&type=$type", '', true) : '';?>
        <tr id='overtime<?php echo $overtime->id;?>' >
          <td class='idTD'>
            <?php if($batchReview):?>
            <label class='checkbox-inline'><input type='checkbox' name='overtimeIDList[]' value='<?php echo $overtime->id;?>'/> <?php echo $overtime->id;?></label>
            <?php else:?>
            <?php echo $overtime->id;?>
            <?php endif;?>
          </td>
          <td><?php echo zget($users, $overtime->createdBy);?></td>
          <td class='visible-lg'><?php echo zget($deptList, $overtime->dept);?></td>
          <td><?php echo zget($this->lang->overtime->typeList, $overtime->type);?></td>
          <td><?php echo formatTime($overtime->begin . ' ' . $overtime->start, DT_DATETIME2);?></td>
          <td><?php echo formatTime($overtime->end . ' ' . $overtime->finish, DT_DATETIME2);?></td>
          <td class='visible-lg'><?php echo $overtime->hours == 0 ? '' : $overtime->hours;?></td>
          <td class='text-left' title='<?php echo $overtime->desc?>'><?php echo $overtime->desc;?></td>
          <td class='overtime-<?php echo $overtime->status?>'><?php echo $overtime->statusLabel;?></td>
          <td class='actionTD text-left'>
            <?php
            if($viewUrl) echo baseHTML::a($viewUrl, $lang->detail, "data-toggle='modal'");
            if($type == 'personal')
            {
                $switchLabel = $overtime->status == 'wait' ? $lang->overtime->cancel : $lang->overtime->commit;
                if(strpos(',wait,draft,', ",$overtime->status,") !== false)
                {
                    extCommonModel::printLink('oa.overtime', 'switchstatus', "id=$overtime->id", $switchLabel, "class='reload'");
                }
                else
                {
                    echo baseHTML::a('###', $switchLabel,  "disabled='disabled'");
                }
                if(strpos(',wait,draft,reject,', ",$overtime->status,") !== false)
                {
                    extCommonModel::printLink('oa.overtime', 'edit',   "id=$overtime->id", $lang->edit,   "data-toggle='modal'");
                    extCommonModel::printLink('oa.overtime', 'delete', "id=$overtime->id", $lang->delete, "class='deleter'");
                }
                else
                {
                    echo baseHTML::a('###', $lang->edit,   "disabled='disabled'");
                    echo baseHTML::a('###', $lang->delete, "disabled='disabled'");
                }
            }
            else
            {
                $canReview = $this->overtime->isClickable($overtime, 'review');

                if($canReview)
                {
                    echo baseHTML::a(inlink('review', "id=$overtime->id&status=pass"),   $lang->overtime->reviewStatusList['pass'],   "class='reviewPass'");
                    echo baseHTML::a(inlink('review', "id=$overtime->id&status=reject"), $lang->overtime->reviewStatusList['reject'], "data-toggle='modal'");
                }
                else
                {
                    echo baseHTML::a('javascript:;', $lang->overtime->reviewStatusList['pass'],   "class='disabled'");
                    echo baseHTML::a('javascript:;', $lang->overtime->reviewStatusList['reject'], "class='disabled'");
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
