<?php
/**
 * The browse of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     risk
 * @version     $Id: browse.html.php 4903 2020-09-04 09:32:59Z lyc $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . "common/view/header.html.php"?>
<style>
#querybox #searchform{border-bottom: 1px solid #ddd; margin-bottom: 20px;}
</style>
<?php
js::set('pageSummary', $lang->risk->pageSummary);
js::set('checkedSummary', $lang->risk->checkedSummary);
js::set('pageRiskSummary', $lang->risk->pageRiskSummary);
js::set('checkedRiskSummary', $lang->risk->checkedRiskSummary);
js::set('browseType', $browseType);
?>
<?php $hasRisklib = helper::hasFeature('risklib');?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toobar pull-left">
    <?php
    $menus = customModel::getFeatureMenu($this->moduleName, $this->methodName);
    foreach($menus as $menuItem)
    {
        $active = $menuItem->name == $browseType ? ' btn-active-text' : '';
        echo html::a($this->createLink('risk', 'browse', "projectID=$projectID&from=$from&browseType=$menuItem->name"), "<span class='text'>{$menuItem->text}</span> " . ($browseType == $menuItem->name ? "<span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active' data-app='{$app->tab}'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->risk->byQuery;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group'>
    <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export ?></span> <span class="caret"></span></button>
    <ul class='dropdown-menu pull-right' id='exportActionMenu'>
        <?php
        $class = common::hasPriv('risk', 'export') ? "" : "class='disabled'";
        $misc  = common::hasPriv('risk', 'export') ? "class='export'" : "class='disabled'";
        $link  = common::hasPriv('risk', 'export') ? $this->createLink('risk', 'export', "objectID=$projectID&browseType=$browseType&orderBy=$orderBy") : '#';
        echo "<li $class>" . html::a($link, $lang->risk->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <div class='btn-group'>
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-import muted"></i> <span class="text"><?php echo $lang->import;?></span> <span class="caret"></span></button>
      <ul class='dropdown-menu pull-right' id='importActionMenu'>
      <?php
      $class = (common::hasPriv('risk', 'importFromLib') and $hasRisklib) ? '' : "class=disabled";
      $misc  = (common::hasPriv('risk', 'importFromLib') and $hasRisklib) ? "data-app='{$app->tab}'" : "class=disabled";
      $link  = (common::hasPriv('risk', 'importFromLib') and $hasRisklib) ? $this->createLink('risk', 'importFromLib', "projectID=$projectID&from=$from") : '#';
      echo "<li $class>" . html::a($link, $lang->risk->importFromLib, '', $misc) . "</li>";
      ?>
      </ul>
    </div>
    <div class='btn-group dropdown'>
      <?php
      if(common::hasPriv('risk', 'create'))
      {
          $actionLink = $this->createLink('risk', 'create', "projectID=$projectID&from=$from");
          echo html::a($actionLink, "<i class='icon icon-plus'></i> {$lang->risk->create}", '', "class='btn btn-primary' data-app='{$app->tab}'");
      }
      elseif(common::hasPriv('risk', 'batchCreate'))
      {
          $actionLink = $this->createLink('risk', 'batchCreate', "projectID=$projectID&from=$from");
          echo html::a($actionLink, "<i class='icon icon-plus'></i> {$lang->risk->batchCreate}", '', "class='btn btn-primary' data-app='{$app->tab}'");
      }
      ?>
      <?php if((common::hasPriv('risk', 'create')) and (common::hasPriv('risk', 'batchCreate'))):?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li><?php echo html::a($actionLink, $lang->risk->create, '', "data-app='{$app->tab}'");?></li>
        <li><?php echo html::a($this->createLink('risk', 'batchCreate', "projectID=$projectID&from=$from"), $lang->risk->batchCreate, '', "data-app='{$app->tab}'");?></li>
      </ul>
      <?php endif;?>
    </div>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=risk');?>" data-module='risk'></div>
<div id="mainContent" class="main-table">
  <?php if(empty($risks)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->noData;?></span>
      <?php if(common::hasPriv('risk', 'create')):?>
      <?php echo html::a($this->createLink('risk', 'create', "projectID=$projectID&from=$from"), "<i class='icon icon-plus'></i> " . $lang->risk->create, '', "class='btn btn-info' data-app='{$app->tab}'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class="main-table table-risk" method="post" id="riskForm">
    <table class="table has-sort-head" id='riskList'>
      <?php $canBatchImportToLib = (common::hasPriv('risk', 'batchImportToLib') and $hasRisklib);?>
      <?php $vars = "projectID=$projectID&from=$from&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
      <thead>
        <tr>
          <th class='text-left w-90px'>
            <?php
            if($canBatchImportToLib) echo "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>";
            common::printOrderLink('id', $orderBy, $vars, $lang->risk->id);
            ?>
          </th>
          <th class='text-left c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->risk->name);?></th>
          <th class='c-pri text-center' title='<?php echo $lang->risk->pri;?>'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->risk->priAB);?></th>
          <th class='c-rate text-center'><?php common::printOrderLink('rate', $orderBy, $vars, $lang->risk->rate);?></th>
          <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->risk->status);?></th>
          <th class='c-category'><?php common::printOrderLink('category', $orderBy, $vars, $lang->risk->category);?></th>
          <th class='c-identifiedDate'><?php common::printOrderLink('identifiedDate', $orderBy, $vars, $lang->risk->identifiedDate);?></th>
          <th class='c-assignedTo'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->risk->assignedTo);?></th>
          <th class='c-strategy'><?php common::printOrderLink('strategy', $orderBy, $vars, $lang->risk->strategy);?></th>
          <th class='c-actions-7 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $showDividing = ((common::hasPriv('risk', 'track') or common::hasPriv('risk', 'close') or common::hasPriv('risk', 'cancel') or common::hasPriv('risk', 'hangup')) and (common::hasPriv('risk', 'activate') or common::hasPriv('effort', 'createForObject') or common::hasPriv('risk', 'edit')));?>
        <?php foreach($risks as $risk):?>
        <tr data-id='<?php echo $risk->id ?>' data-status='<?php echo $risk->status?>'>
          <td>
            <?php echo $canBatchImportToLib ? html::checkbox('riskIDList', array($risk->id => '')) . sprintf('%03d',$risk->id) : sprintf('%03d',$risk->id);?>
          </td>
          <td class='c-name' title=<?php echo $risk->name;?>>
            <?php
            if(commonModel::hasPriv('risk', 'view'))
            {
                echo html::a($this->createLink('risk', 'view', "riskID=$risk->id&from=$from"), $risk->name, '', "data-app='{$app->tab}'");
            }
            else
            {
                echo $risk->name;
            }
            ?>
          </td>
          <td class='text-center'><?php echo "<span class='pri-{$risk->pri}'>" . zget($lang->risk->priList, $risk->pri) . "</span>";?></td>
          <td class='text-center'><?php echo $risk->rate;?></td>
          <td class='status-risk status-<?php echo $risk->status?>'><?php echo zget($lang->risk->statusList, $risk->status);?></td>
          <td><?php echo zget($lang->risk->categoryList, $risk->category);?></td>
          <td><?php echo $risk->identifiedDate == '0000-00-00' ? '' : $risk->identifiedDate;?></td>
          <td><?php echo $this->risk->printAssignedHtml($risk, $users);?></td>
          <td><?php echo zget($lang->risk->strategyList, $risk->strategy);?></td>
          <td class='c-actions'>
            <?php
            $params = "riskID=$risk->id";
            common::printIcon('risk', 'track', $params, $risk, "list", 'checked', '', 'iframe', true);
            common::printIcon('risk', 'close', $params, $risk, "list", '', '', 'iframe', true);
            common::printIcon('risk', 'cancel', $params, $risk, "list", '', '', 'iframe', true);
            common::printIcon('risk', 'hangup', $params, $risk, "list", 'pause', '', 'iframe', true);
            if($showDividing) echo '<div class="dividing-line"></div>';
            common::printIcon('risk', 'activate', $params, $risk, "list", '', '', 'iframe', true);
            common::printIcon('effort', 'createForObject', "objectType=risk&objectID=$risk->id", '', 'list', 'time', '', 'iframe', true, '', $lang->risk->effort);
            common::printIcon('risk', 'edit', $params . "&from=$from", $risk, "list");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php if($canBatchImportToLib):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php echo html::a('#batchImportToLib', $lang->risk->importToLib, '', 'class="btn" data-toggle="modal" id="importToLib"');?>
      </div>
      <?php endif;?>
      <div class="table-statistic"></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  <?php endif;?>
  </form>
</div>

<div class="modal fade" id="batchImportToLib">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->risk->importToLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('risk', 'batchImportToLib');?>'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->risk->lib;?></th>
              <td>
                <?php echo html::select('lib', $libs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approveIssue') and !common::hasPriv('assetlib', 'batchApproveIssue')):?>
            <tr>
              <th><?php echo $lang->risk->approver;?></th>
              <td>
                <?php echo html::select('assignedTo', $approvers, '', "class='form-control chosen'");?>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='2' class='text-center'>
                <?php echo html::hidden('riskIDList', '');?>
                <?php echo html::submitButton($lang->import, '', 'btn btn-primary');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . "common/view/footer.html.php"?>
