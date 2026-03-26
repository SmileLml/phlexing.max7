<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     execution
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    foreach($this->lang->execution->featureBar['effortcalendar'] as $period => $label)
    {
        if($period == 'calendar' and !common::hasPriv('execution', 'effortcalendar')) continue;
        $method = $period == 'calendar' ? 'effortcalendar' : 'effort';
        $vars   = $period == 'calendar' ? "executionID=$executionID" : "executionID=$executionID&date=$period";
        $active = $period == $date ? 'btn-active-text' : '';
        $label  = "<span class='text'>$label</span>";
        if($period == $date) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
        echo html::a(inlink($method, $vars), $label, '', "class='btn btn-link $active' id='{$period}'");
    }
    ?>
    <div class='input-control space w-150px'>
      <?php echo html::input('date', $today, "class='form-date form-control' id='$today' onchange='changeDate($executionID, this.value)'");?>
    </div>
    <div class='input-control space w-150px'>
      <?php echo html::select('account', $users, $userID, "class='form-control chosen' id='account' onchange='changeUser($executionID, \"$date\", this.value)'");?>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printIcon('effort', 'export', "userID=$userID&orderBy=date_asc", '', 'button', '', '', 'export iframe');?>
  </div>
</div>
<div id="mainContent">
  <form class='main-table' data-ride='table' id='effortForm' method='post' action='<?php echo $this->createLink('effort', 'batchEdit', "from=browse&userID=" . ($userID == 'all' ? '' : $userID))?>'>
    <table id='effortList' class='table has-sort-head table-fixed'>
      <?php $vars = "executionID=$executionID&date=$date&userID=$userID&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage"; ?>
      <thead>
        <tr>
          <th class='c-id'>
            <?php $canExport = common::hasPriv('effort', 'export');?>
            <?php if($canExport):?>
            <div class="checkbox-primary check-all"><label></label></div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='w-date'> <?php common::printOrderLink('date',     $orderBy, $vars, $lang->effort->date);?></th>
          <th class='w-80px'> <?php common::printOrderLink('account',  $orderBy, $vars, $lang->effort->account);?></th>
          <th>                <?php common::printOrderLink('work',     $orderBy, $vars, $lang->effort->work);?></th>
          <th class='w-100px'><?php common::printOrderLink('consumed', $orderBy, $vars, $lang->effort->consumed);?></th>
          <th class='w-90px'> <?php common::printOrderLink('left',     $orderBy, $vars, $lang->effort->left);?></th>
          <th width='350'>    <?php common::printOrderLink('objectID', $orderBy, $vars, $lang->effort->objectType);?></th>
        </tr>
      </thead>
      <tbody>
        <?php $times = 0?>
        <?php foreach($efforts as $effort):?>
        <tr>
          <td>
            <?php if($canExport):?>
            <?php echo html::checkbox('effortList', array($effort->id => sprintf('%03d', $effort->id)));?>
            <?php else:?>
            <?php printf('%03d', $effort->id);?>
            <?php endif;?>
          </td>
          <td><?php echo $effort->date;?></td>
          <td><?php echo $accounts[$effort->account];?></td>
          <td class='text-left'><?php echo html::a($this->createLink('effort', 'view', "id=$effort->id&from=my", '', true), $effort->work, '', "class='effortview iframe'");?></td>
          <td><?php echo helper::formatHours($effort->consumed);?></td>
          <td><?php echo helper::formatHours($effort->left);?></td>
          <td class='text-left'><?php if($effort->objectType != 'custom') echo html::a($this->createLink($effort->objectType, 'view', "id=$effort->objectID"),$effort->objectTitle);?></td>
        </tr>
        <?php $times += $effort->consumed;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($efforts):?>
    <div class='table-footer'>
      <?php if($canExport):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <?php if($times):?>
      <?php printf('<div class="text pull-left" style="line-height:28px;">' . $lang->company->effort->timeStat . '</div>', $times);?>
      <?php endif;?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
