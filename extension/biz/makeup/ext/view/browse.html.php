<?php
/**
 * The browse view file of makeup module of Ranzhi.
 *
 * @copyright   Copyright 2009-2018 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     makeup
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
    foreach($lang->makeup->featureBar['personal'] as $method => $name)
    {
        $class = strtolower($method) == $methodName ? "class='active'" : '';
        if(common::hasPriv('makeup', $method)) echo "<li id='$method' $class>" . html::a($this->createLink('makeup', $method), $name) . '</li>';
    }
    ?>
    </ul>
  </div>

<?php js::set('confirmReview', $lang->makeup->confirmReview)?>
<div id='menuActions'>
  <?php extCommonModel::printLink('oa.makeup', 'export', "mode=all&orderBy={$orderBy}", $lang->exportIcon . $lang->export, "class='btn btn-primary iframe' data-width='700'");?>
  <?php extCommonModel::printLink('oa.makeup', 'create', '', "<i class='icon icon-plus'></i> {$lang->makeup->create}", "data-toggle='modal' class='btn btn-primary'")?>
</div>
<?php if($type != 'browseReview'):?>
<div class='with-side'>
  <div class='side'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-ride='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php extCommonModel::printLink('oa.makeup', $type, "date=$year", $year);?>
            <ul>
              <?php foreach($monthList[$year] as $month):?>
              <li class='<?php echo ($year == $currentYear and $month == $currentMonth) ? 'active' : ''?>'>
                <?php extCommonModel::printLink('oa.makeup', $type, "date=$year$month", $year . $month);?>
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
<?php $batchReview = $type == 'browseReview' && commonModel::hasPriv('makeup', 'batchReview');?>
    <div class='panel'>
      <?php if(empty($makeupList)):?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->noData;?></span>
          <?php if(common::hasPriv('makeup', 'create')):?>
          <?php echo html::a($this->createLink('makeup', 'create'), "<i class='icon icon-plus'></i> " . $lang->makeup->create, '', "class='btn btn-info' data-toggle='modal'");?>
          <?php endif;?>
        </p>
      </div>
      <?php else:?>
      <?php if($batchReview):?>
      <form id='batchReviewForm' method='post' action='<?php echo inlink('batchReview', 'status=pass');?>'>
      <?php endif;?>
      <table class='table table-hover text-center table-fixed tablesorter' id='makeupTable'>
        <thead>
          <tr class='text-center'>
            <?php $vars = "&date={$date}&orderBy=%s";?>
            <th class='w-50px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->makeup->id, 'makeup', $type);?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->makeup->createdBy, 'makeup', $type);?></th>
            <th class='w-100px visible-lg'><?php echo $lang->user->dept;?></th>
            <th class='w-120px'><?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->makeup->begin, 'makeup', $type);?></th>
            <th class='w-120px'><?php commonModel::printOrderLink('end', $orderBy, $vars, $lang->makeup->end, 'makeup', $type);?></th>
            <th class='w-70px visible-lg'><?php commonModel::printOrderLink('hours', $orderBy, $vars, $lang->makeup->hours, 'makeup', $type);?></th>
            <th class='text-left'><?php echo $lang->makeup->desc;?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->makeup->status, 'makeup', $type);?></th>
            <?php if($type == 'personal'):?>
            <?php $class = $this->app->clientLang == 'en' ? 'w-180px' : 'w-140px';?>
            <th class='<?php echo $class;?>'><?php echo $lang->actions;?></th>
            <?php else:?>
            <?php $class = $this->app->clientLang == 'en' ? 'w-130px' : 'w-110px';?>
            <th class='<?php echo $class;?>'><?php echo $lang->actions;?></th>
            <?php endif;?>
          </tr>
        </thead>
        <?php foreach($makeupList as $makeup):?>
        <?php $viewUrl = commonModel::hasPriv('makeup', 'view') ? $this->createLink('makeup', 'view', "id=$makeup->id&type=$type") : '';?>
        <tr id='makeup<?php echo $makeup->id;?>' >
          <td class='idTD'>
            <?php if($batchReview):?>
            <label class='checkbox-inline'><input type='checkbox' name='makeupIDList[]' value='<?php echo $makeup->id;?>'/> <?php echo $makeup->id;?></label>
            <?php else:?>
            <?php echo $makeup->id;?>
            <?php endif;?>
          </td>
          <td><?php echo zget($users, $makeup->createdBy);?></td>
          <td class='visible-lg'><?php echo zget($deptList, $makeup->dept, ' ');?></td>
          <td><?php echo formatTime($makeup->begin . ' ' . $makeup->start, DT_DATETIME2);?></td>
          <td><?php echo formatTime($makeup->end . ' ' . $makeup->finish, DT_DATETIME2);?></td>
          <td class='visible-lg'><?php echo $makeup->hours == 0 ? '' : $makeup->hours;?></td>
          <td class='text-left' title='<?php echo $makeup->desc?>'><?php echo $makeup->desc;?></td>
          <td class='makeup-<?php echo $makeup->status?>'><?php echo $makeup->statusLabel;?></td>
          <td class='actionTD text-left'>
            <?php
            if($viewUrl) echo baseHTML::a($viewUrl, $lang->detail, "data-toggle='modal'");
            if($type == 'personal')
            {
                $switchLabel = $makeup->status == 'wait' ? $lang->makeup->cancel : $lang->makeup->commit;
                if(strpos(',wait,draft,', ",$makeup->status,") !== false)
                {
                    extCommonModel::printLink('oa.makeup', 'switchstatus', "id=$makeup->id", $switchLabel, "class='reload'");
                }
                else
                {
                    echo baseHTML::a('###', $switchLabel,  "disabled='disabled'");
                }
                if(strpos(',wait,draft,reject,', ",$makeup->status,") !== false)
                {
                    extCommonModel::printLink('oa.makeup', 'edit',   "id=$makeup->id", $lang->edit,   "data-toggle='modal'");
                    extCommonModel::printLink('oa.makeup', 'delete', "id=$makeup->id", $lang->delete, "class='deleter'");
                }
                else
                {
                    echo baseHTML::a('###', $lang->edit,   "disabled='disabled'");
                    echo baseHTML::a('###', $lang->delete, "disabled='disabled'");
                }
            }
            else
            {
                $canReview = $this->makeup->isClickable($makeup, 'review');

                if($canReview)
                {
                    echo baseHTML::a(inlink('review', "id=$makeup->id&status=pass"),   $lang->makeup->reviewStatusList['pass'],   "class='reviewPass'");
                    echo baseHTML::a(inlink('review', "id=$makeup->id&status=reject"), $lang->makeup->reviewStatusList['reject'], "data-toggle='modal'");
                }
                else
                {
                    echo baseHTML::a('javascript:;', $lang->makeup->reviewStatusList['pass'],   "class='disabled'");
                    echo baseHTML::a('javascript:;', $lang->makeup->reviewStatusList['reject'], "class='disabled'");
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
