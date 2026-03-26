<?php
/**
 * The browse view file of lieu module of Ranzhi.
 *
 * @copyright   Copyright 2009-2018 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     lieu
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
    foreach($lang->lieu->featureBar['personal'] as $method => $name)
    {
        $class = strtolower($method) == $methodName ? "class='active'" : '';
        if(common::hasPriv('lieu', $method)) echo "<li id='$method' $class>" . html::a($this->createLink('lieu', $method), $name) . '</li>';
    }
    ?>
    </ul>
  </div>

<?php js::set('confirmReview', $lang->lieu->confirmReview)?>
<div id='menuActions'>
  <?php extCommonModel::printLink('oa.lieu', 'create', "", "<i class='icon icon-plus'></i> {$lang->lieu->create}", "data-toggle='modal' class='btn btn-primary'")?>
</div>
<?php if($type != 'browseReview'):?>
<div class='with-side'>
  <div class='side'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-ride='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php extCommonModel::printLink('oa.lieu', $type, "date=$year", $year);?>
            <ul>
              <?php foreach($monthList[$year] as $month):?>
              <li class='<?php echo ($year == $currentYear and $month == $currentMonth) ? 'active' : ''?>'>
                <?php extCommonModel::printLink('oa.lieu', $type, "date=$year$month", $year . $month);?>
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
<?php $batchReview = $type == 'browseReview' && commonModel::hasPriv('lieu', 'batchReview');?>
    <div class='panel'>
      <?php if(empty($lieuList)):?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->noData;?></span>
          <?php if(common::hasPriv('lieu', 'create')):?>
          <?php echo html::a($this->createLink('lieu', 'create'), "<i class='icon icon-plus'></i> " . $lang->lieu->create, '', "class='btn btn-info' data-toggle='modal'");?>
          <?php endif;?>
        </p>
      </div>
      <?php else:?>
      <?php if($batchReview):?>
      <form id='batchReviewForm' method='post' action='<?php echo inlink('batchReview', 'status=pass');?>'>
      <?php endif;?>
      <table class='table table-hover text-center table-fixed tablesorter' id='lieuTable'>
        <thead>
          <tr class='text-center'>
            <?php $vars = "&date={$date}&orderBy=%s";?>
            <th class='w-50px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->lieu->id, 'lieu', $type);?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->lieu->createdBy, 'lieu', $type);?></th>
            <th class='w-100px visible-lg'><?php echo $lang->user->dept;?></th>
            <th class='w-130px'><?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->lieu->begin, 'lieu', $type);?></th>
            <th class='w-130px'><?php commonModel::printOrderLink('end', $orderBy, $vars, $lang->lieu->end, 'lieu', $type);?></th>
            <th class='w-70px'><?php commonModel::printOrderLink('hours', $orderBy, $vars, $lang->lieu->hours, 'lieu', $type);?></th>
            <th class='text-left'><?php echo $lang->lieu->desc;?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->lieu->status, 'lieu', $type);?></th>
            <?php if($type == 'personal'):?>
            <?php $class = $this->app->clientLang == 'en' ? 'w-180px' : 'w-140px';?>
            <th class='<?php echo $class;?>'><?php echo $lang->actions;?></th>
            <?php else:?>
            <?php $class = $this->app->clientLang == 'en' ? 'w-130px' : 'w-110px';?>
            <th class='<?php echo $class;?>'><?php echo $lang->actions;?></th>
            <?php endif;?>
          </tr>
        </thead>
        <?php foreach($lieuList as $lieu):?>
        <?php $viewUrl = commonModel::hasPriv('lieu', 'view') ? $this->createLink('lieu', 'view', "id={$lieu->id}&type=$type") : '';?>
        <tr id='lieu<?php echo $lieu->id;?>' >
          <td class='idTD'>
            <?php if($batchReview):?>
            <label class='checkbox-inline'><input type='checkbox' name='lieuIDList[]' value='<?php echo $lieu->id;?>'/> <?php echo $lieu->id;?></label>
            <?php else:?>
            <?php echo $lieu->id;?>
            <?php endif;?>
          </td>
          <td><?php echo zget($users, $lieu->createdBy);?></td>
          <td class='visible-lg'><?php echo zget($deptList, $lieu->dept, '');?></td>
          <td><?php echo formatTime($lieu->begin . ' ' . $lieu->start, DT_DATETIME2);?></td>
          <td><?php echo formatTime($lieu->end . ' ' . $lieu->finish, DT_DATETIME2);?></td>
          <td><?php echo $lieu->hours;?></td>
          <td class='text-left' title='<?php echo $lieu->desc;?>'><?php echo $lieu->desc;?></td>
          <td class='lieu-<?php echo $lieu->status?>'><?php echo $lieu->statusLabel;?></td>
          <td class='actionTD text-left'>
            <?php
            if($viewUrl) echo baseHTML::a($viewUrl, $lang->detail, "data-toggle='modal'");
            if($type == 'personal')
            {
                $switchLabel = $lieu->status == 'wait' ? $lang->lieu->cancel : $lang->lieu->commit;
                if(strpos(',wait,draft,', ",$lieu->status,") !== false)
                {
                    extCommonModel::printLink('oa.lieu', 'switchstatus', "id={$lieu->id}", $switchLabel, "class='reload'");
                }
                else
                {
                    echo baseHTML::a('###', $switchLabel,  "disabled='disabled'");
                }
                if(strpos(',wait,draft,reject,', ",$lieu->status,") !== false)
                {
                    extCommonModel::printLink('oa.lieu', 'edit',   "id={$lieu->id}", $lang->edit,   "data-toggle='modal'");
                    extCommonModel::printLink('oa.lieu', 'delete', "id={$lieu->id}", $lang->delete, "class='deleter'");
                }
                else
                {
                    echo baseHTML::a('###', $lang->edit,   "disabled='disabled'");
                    echo baseHTML::a('###', $lang->delete, "disabled='disabled'");
                }
            }
            else
            {
                $canReview = $this->lieu->isClickable($lieu, 'review');

                if($canReview)
                {
                    echo baseHTML::a(inlink('review', "id={$lieu->id}&status=pass"),   $lang->lieu->reviewStatusList['pass'],   "class='reviewPass'");
                    echo baseHTML::a(inlink('review', "id={$lieu->id}&status=reject"), $lang->lieu->reviewStatusList['reject'], "data-toggle='modal'");
                }
                else
                {
                    echo baseHTML::a('javascript:;', $lang->lieu->reviewStatusList['pass'],   "class='disabled'");
                    echo baseHTML::a('javascript:;', $lang->lieu->reviewStatusList['reject'], "class='disabled'");
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
