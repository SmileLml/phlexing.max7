<?php
/**
 * The opportunity of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     assetlib
 * @version     $Id: opportunity.html.php 4903 2021-06-25 13:24:59Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . "common/view/header.html.php"?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toobar pull-left">
    <div class='btn-group'>
      <a href='javascript:;' class='btn btn-link btn-limit' data-toggle='dropdown'><span class='text' title='<?php echo zget($libs, $libID);?>'><?php echo zget($libs, $libID);?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu'>
        <?php foreach($libs as $key => $lib) echo "<li class='" . ($libID == $key ? 'active' : '') . "' title='{$lib}'>" . html::a($this->createLink('assetlib', 'opportunity', "libID=$key"), $lib) . "</li>";?>
      </ul>
    </div>
    <?php
    $menus = $lang->assetlib->featureBar['opportunity'];
    foreach($menus as $key => $menuItem)
    {
        $active = $key == $browseType ? ' btn-active-text' : '';
        echo html::a($this->createLink('assetlib', 'opportunity', "libID=$libID&browseType=$key"), "<span class='text'>{$menuItem}</span>", '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->assetlib->byQuery;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php
    if(common::hasPriv('assetlib', 'opportunityLibView'))
    {
        $link = helper::createLink('assetlib', 'opportunityLibView', "libID=$libID");
        echo html::a($link, "<i class='icon icon-list-alt muted'> </i>" . $this->lang->assetlib->libView, '', "class='btn btn-link'");
    }
    ?>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown' id='importAction'><i class='icon icon-import muted'></i> <?php echo $lang->import ?><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right' id='importActionMenu'>
      <?php
      $class = common::hasPriv('assetlib', 'importOpportunity') ? '' : "class=disabled";
      $link  = common::hasPriv('assetlib', 'importOpportunity') ?  $this->createLink('assetlib', 'importOpportunity', "libID=$libID") : '#';
      echo "<li $class>" . html::a($link, $lang->assetlib->importOpportunity) . "</li>";
      ?>
      </ul>
    </div>
  </div>
</div>
<div class="cell <?php if($browseType == 'bysearch') echo 'show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=opportunityLib');?>" data-module='opportunityLib'></div>
<div id="mainContent" class="main-table">
  <?php if(empty($opportunities)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
  </div>
  <?php else:?>
  <form  class='main-table' id='opportunityForm' method='post' data-ride="table">
    <table class="table has-sort-head" id='opportunityList'>
      <?php
      $vars = "libID=$libID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
      $canBatchAssignTo = common::hasPriv('assetlib', 'batchAssignToOpportunity');
      $canBatchApprove  = common::hasPriv('assetlib', 'batchApproveOpportunity');
      $canBatchRemove   = common::hasPriv('assetlib', 'batchRemoveOpportunity');
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
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->opportunity->name);?></th>
          <th class='c-pri' title='<?php echo $lang->pri;?>'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->assetlib->priAB);?></th>
          <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->opportunity->status);?></th>
          <th class="c-type"><?php common::printOrderLink('type', $orderBy, $vars, $lang->opportunity->type);?></th>
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
        <?php foreach($opportunities as $opportunity):?>
        <tr>
          <td>
            <?php echo $canBatchAction ? html::checkbox('opportunityIDList', array($opportunity->id => '')) . sprintf('%03d',$opportunity->id) : sprintf('%03d',$opportunity->id);?>
          </td>
          <td class='c-name' title="<?php echo $opportunity->name?>"><?php echo html::a($this->createLink('assetlib', 'opportunityView', "opportunityID=$opportunity->id"), $opportunity->name);?></td>
          <td id='pri'><?php echo "<span class='pri-{$opportunity->pri}'>" . zget($lang->opportunity->priList, $opportunity->pri) . "</span>";?></td>
          <td><?php echo "<span class='status-{$opportunity->status}'>" . zget($lang->assetlib->statusList, $opportunity->status) . '</span>';?></td>
          <td><?php echo zget($lang->opportunity->typeList, $opportunity->type);?></td>
          <?php if($browseType == 'draft'):?>
          <td><?php echo $this->assetlib->printAssignedHtml($opportunity, $approvers, 'opportunity');?></td>
          <?php endif;?>
          <td class='c-openedBy' title="<?php echo zget($users, $opportunity->createdBy);?>"><?php echo zget($users, $opportunity->createdBy);?></td>
          <td><?php echo helper::isZeroDate($opportunity->createdDate) ? '' : substr($opportunity->createdDate, 0, 11);?></td>
          <?php if($browseType == 'all' or $browseType == 'bysearch'):?>
          <?php $assignedTo = $opportunity->status == 'active' ? zget($approvers, $opportunity->assignedTo) : '';?>
          <td class='c-assignedTo' title="<?php echo $assignedTo;?>"><?php echo $assignedTo;?></td>
          <td><?php echo helper::isZeroDate($opportunity->approvedDate) ? '' : $opportunity->approvedDate;?></td>
          <?php endif;?>
          <td class='c-actions'>
            <?php
            $vars = "opportunityID={$opportunity->id}";
            common::printIcon('assetlib', 'editOpportunity',    $vars, $opportunity, 'list', 'edit');
            common::printIcon('assetlib', 'approveOpportunity', $vars, $opportunity, 'list', 'glasses', '', 'iframe', true);
            common::printIcon('assetlib', 'removeOpportunity',  $vars, $opportunity, 'list', 'unlink', 'hiddenwin');
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
                  $actionLink = $this->createLink('assetlib', 'batchApproveOpportunity', "libID=$libID&result=$key");
                  echo html::a("javascript:$(\".table-actions #approve\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\", \"#opportunityList\")", $value);
              }
              ?>
            </li>
          </ul>
        </div>
        <?php endif;?>

        <?php if($canBatchAssignTo and $browseType == 'draft'):?>
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->assetlib->assignedTo;?><span class="caret"></span></button>
          <?php
          $withSearch = count($approvers) > 10;
          $actionLink = $this->createLink('assetlib', 'batchAssignToOpportunity', "libID=$libID");
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
                  echo html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\", \"#opportunityList\")", $value, '', $searchKey);
              }
              ?>
            </div>
          </div>
        </div>
        <?php endif;?>
        <?php if($canBatchRemove):?>
        <?php
        $removeLink = inlink('batchRemoveOpportunity');
        echo html::commonButton($lang->assetlib->removeOpportunity, "onclick=\"setFormAction('$removeLink', 'hiddenwin', this)\"");
        ?>
        <?php endif;?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . "common/view/footer.html.php"?>
