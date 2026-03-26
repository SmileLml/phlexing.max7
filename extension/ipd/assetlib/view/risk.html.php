<?php
/**
 * The risk of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     assetlib
 * @version     $Id: risk.html.php 4903 2021-06-25 10:54:59Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . "common/view/header.html.php"?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toobar pull-left">
    <div class='btn-group'>
      <a href='javascript:;' class='btn btn-link btn-limit' data-toggle='dropdown'><span class='text' title='<?php echo zget($libs, $libID);?>'><?php echo zget($libs, $libID);?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu'>
        <?php foreach($libs as $key => $lib) echo "<li class='text-ellipsis " . ($libID == $key ? 'active' : '') . "' title='{$lib}'>" . html::a($this->createLink('assetlib', 'risk', "libID=$key"), $lib, '_self', "title='{$lib}'") . "</li>";?>
      </ul>
    </div>
    <?php
    $menus = $lang->assetlib->featureBar['risk'];
    foreach($menus as $key => $menuItem)
    {
        $active = $key == $browseType ? ' btn-active-text' : '';
        echo html::a($this->createLink('assetlib', 'risk', "libID=$libID&browseType=$key"), "<span class='text'>{$menuItem}</span>", '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php
    if(common::hasPriv('assetlib', 'riskLibView'))
    {
        $link = helper::createLink('assetlib', 'riskLibView', "libID=$libID");
        echo html::a($link, "<i class='icon icon-list-alt muted'> </i>" . $this->lang->assetlib->libView, '', "class='btn btn-link'");
    }
    ?>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown' id='importAction'><i class='icon icon-import muted'></i> <?php echo $lang->import ?><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right' id='importActionMenu'>
      <?php
      $class = common::hasPriv('assetlib', 'importRisk') ? '' : "class=disabled";
      $link  = common::hasPriv('assetlib', 'importRisk') ?  $this->createLink('assetlib', 'importRisk', "libID=$libID") : '#';
      echo "<li $class>" . html::a($link, $lang->assetlib->importRisk) . "</li>";
      ?>
      </ul>
    </div>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox"  data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=riskLib');?>" data-module='riskLib'></div>
<div id="mainContent" class="main-table">
  <?php if(empty($risks)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
  </div>
  <?php else:?>
  <form  class='main-table' id='riskForm' method='post' data-ride="table">
    <table class="table has-sort-head" id='riskList'>
      <?php
      $vars = "libID=$libID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
      $canBatchAssignTo = common::hasPriv('assetlib', 'batchAssignToRisk');
      $canBatchApprove  = common::hasPriv('assetlib', 'batchApproveRisk');
      $canBatchRemove   = common::hasPriv('assetlib', 'batchRemoveRisk');
      $canBatchAction   = ($browseType == 'all' or $browseType == 'bysearch') ? ($canBatchApprove or $canBatchRemove) : ($canBatchAssignTo or $canBatchApprove or $canBatchRemove);
      ?>
      <thead>
        <tr>
          <th class='text-left c-id'>
            <?php
            if($canBatchAction) echo "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>";
            common::printOrderLink('id', $orderBy, $vars, $lang->assetlib->id);
            ?>
          </th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->risk->name);?></th>
          <th class='c-pri text-center' title='<?php echo $lang->pri;?>'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->assetlib->priAB);?></th>
          <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->risk->status);?></th>
          <th class="c-strategy w-90px"><?php common::printOrderLink('strategy', $orderBy, $vars, $lang->risk->strategy);?></th>
          <?php if($browseType == 'draft'):?>
          <th class='c-assignedTo'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assetlib->assignedTo);?></th>
          <?php endif;?>
          <th class='c-createdBy'><?php common::printOrderLink('createdBy', $orderBy, $vars, $lang->assetlib->createdBy);?></th>
          <th class='c-createdDate w-100px'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->assetlib->createdDate);?></th>
          <?php if($browseType == 'all' or $browseType == 'bysearch'):?>
          <th class='c-assignedTo'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assetlib->approved);?></th>
          <th class='c-approvedDate'><?php common::printOrderLink('approvedDate', $orderBy, $vars, $lang->assetlib->approvedDate);?></th>
          <?php endif;?>
          <th class='c-actions-3'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($risks as $risk):?>
        <tr>
          <td>
            <?php echo $canBatchAction ? html::checkbox('riskIDList', array($risk->id => '')) . sprintf('%03d',$risk->id) : sprintf('%03d',$risk->id);?>
          </td>
          <td class='c-name' title="<?php echo $risk->name?>"><?php echo html::a($this->createLink('assetlib', 'riskView', "riskID=$risk->id"), $risk->name);?></td>
          <td class='text-center'><?php echo "<span class='pri-{$risk->pri}'>" . zget($lang->risk->priList, $risk->pri) . "</span>";?></td>
          <td><?php echo "<span class='status-{$risk->status}'>" . zget($lang->assetlib->statusList, $risk->status) . '</span>';?></td>
          <td><?php echo zget($lang->risk->strategyList, $risk->strategy);?></td>
          <?php if($browseType == 'draft'):?>
          <td><?php echo $this->assetlib->printAssignedHtml($risk, $approvers, 'risk');?></td>
          <?php endif;?>
          <td class='c-openedBy' title="<?php echo zget($users, $risk->createdBy);?>"><?php echo zget($users, $risk->createdBy);?></td>
          <td><?php echo helper::isZeroDate($risk->createdDate) ? '' : substr($risk->createdDate, 0, 11);?></td>
          <?php if($browseType == 'all' or $browseType == 'bysearch'):?>
          <?php $assignedTo = $risk->status == 'active' ? zget($users, $risk->assignedTo) : '';?>
          <td class='c-assignedTo' title='<?php echo $assignedTo;?>'><?php echo $assignedTo;?></td>
          <td><?php echo helper::isZeroDate($risk->approvedDate) ? '' : $risk->approvedDate;?></td>
          <?php endif;?>
          <td class='c-actions'>
            <?php
            $vars = "riskID={$risk->id}";
            common::printIcon('assetlib', 'editRisk',    $vars, $risk, 'list', 'edit');
            common::printIcon('assetlib', 'approveRisk', $vars, $risk, 'list', 'glasses', '', 'iframe', true);
            common::printIcon('assetlib', 'removeRisk',  $vars, $risk, 'list', 'unlink', 'hiddenwin');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php if($canBatchAction):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
        <?php if($canBatchApprove):?>
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->assetlib->approve;?> <span class="caret"></span></button>
          <ul class="dropdown-menu" role='menu'>
            <li>
              <?php
              foreach($lang->assetlib->resultList as $key => $value)
              {
                  if(empty($key)) continue;
                  $actionLink = $this->createLink('assetlib', 'batchApproveRisk', "libID=$libID&result=$key");
                  echo html::a("javascript:$(\".table-actions #approve\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\", \"#riskList\")", $value);
              }
              ?>
            </li>
          </ul>
        </div>
        <?php endif;?>

        <?php if($canBatchAssignTo and $browseType == 'draft'):?>
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->assetlib->assignedTo;?> <span class="caret"></span></button>
          <?php
          $withSearch = count($approvers) > 10;
          $actionLink = $this->createLink('assetlib', 'batchAssignToRisk', "libID=$libID");
          echo html::select('assignedTo', $approvers, '', 'class="hidden"');
          if($withSearch):
          ?>
          <div class="dropdown-menu search-list search-box-sink" data-ride="searchList">
            <div class="input-control search-box has-icon-left has-icon-right search-example">
              <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input">
              <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
              <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
            </div>
          <?php $membersPinYin = common::convert2Pinyin($approvers);?>
          <?php else:?>
          <div class="dropdown-menu search-list">
          <?php endif;?>
            <div class="list-group">
              <?php
              foreach($approvers as $key => $value)
              {
                  if(empty($key)) continue;
                  $searchKey = $withSearch ? ('data-key="' . zget($membersPinYin, $value, '') . " @$key\"") : "data-key='@$key'";
                  echo html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\", \"#riskList\")", $value, '', $searchKey);
              }
              ?>
            </div>
          </div>
        </div>
        <?php endif;?>
        <?php if($canBatchRemove):?>
        <?php
        $removeLink = inlink('batchRemoveRisk');
        echo html::commonButton($lang->assetlib->removeRisk, "onclick=\"setFormAction('$removeLink', 'hiddenwin', this)\"");
        ?>
        <?php endif;?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . "common/view/footer.html.php"?>
