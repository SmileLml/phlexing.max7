<?php
/**
 * The browse view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $hasIssuelib = helper::hasFeature('issuelib');?>
<?php $canBatchImportToLib = (common::hasPriv('issue', 'batchImportToLib') and $hasIssuelib);?>
<?php $canImportFromLib    = (common::hasPriv('issue', 'importFromLib')    and $hasIssuelib);?>
<style>
.c-actions-8 {width:220px !important; text-align: center;}
</style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
      foreach($lang->issue->featureBar['browse'] as $label => $labelName)
      {
          $active = $browseType == $label ? 'btn-active-text' : '';
          echo html::a($this->createLink('issue', 'browse', "projectID=$projectID&from=$from&browseType=" . $label), '<span class="text">' . $labelName . '</span> ' . ($browseType == $label ? "<span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active' data-app='{$app->tab}'");
      }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->issue->search;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group'>
    <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export ?></span> <span class="caret"></span></button>
    <ul class='dropdown-menu pull-right' id='exportActionMenu'>
        <?php
        $class = common::hasPriv('issue', 'export') ? "" : "class='disabled'";
        $misc  = common::hasPriv('issue', 'export') ? "class='export'" : "class='disabled'";
        $link  = common::hasPriv('issue', 'export') ? $this->createLink('issue', 'export', "objectID={$projectID}&from={$from}&browseType={$browseType}&orderBy={$orderBy}") : '#';
        echo "<li $class>" . html::a($link, $lang->issue->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>

    <div class='btn-group'>
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-import muted"></i> <span class="text"><?php echo $lang->import;?></span> <span class="caret"></span></button>
      <ul class='dropdown-menu pull-right' id='importActionMenu'>
      <?php
      $class = $canImportFromLib ? '' : "class=disabled";
      $misc  = $canImportFromLib ? "data-app='{$app->tab}'" : "class=disabled";
      $link  = $canImportFromLib ? $this->createLink('issue', 'importFromLib', "projectID=$projectID&from=$from") : '#';
      echo "<li $class>" . html::a($link, $lang->issue->importFromLib, '', $misc) . "</li>";
      ?>
      </ul>
    </div>
    <?php if(common::hasPriv('issue', 'create') and common::hasPriv('issue', 'batchCreate')):?>
    <div class='btn-group dropdown'>
      <?php
      $actionLink = $this->createLink('issue', 'create', "projectID=$projectID&from=$from");
      echo html::a($actionLink, "<i class='icon icon-plus'></i> {$lang->issue->create}", '', "class='btn btn-primary'");
      ?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li><?php echo html::a($actionLink, $lang->issue->create);?></li>
        <li><?php echo html::a($this->createLink('issue', 'batchCreate', "projectID=$projectID&from=$from"), $lang->issue->batchCreate);?></li>
      </ul>
    </div>
    <?php else:?>
    <?php common::printLink('issue', 'batchCreate', "projectID=$projectID&from=$from", "<i class='icon icon-plus'></i>" . $lang->issue->batchCreate, '', "class='btn btn-secondary'");?>
    <?php common::printLink('issue', 'create', "projectID=$projectID&from=$from", "<i class='icon icon-plus'></i>" . $lang->issue->create, '', "class='btn btn-primary'");?>
    <?php endif;?>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col">
  <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=issue');?>" data-module="issue"></div>
    <?php if($issueList):?>
      <form class="main-table table-issue" data-ride="table" method="post" id="issueForm">
        <table id="issueList" class="table has-sort-head" id="issueTable">
          <thead>
            <tr>
              <?php $vars = "projectID=$projectID&from=$from&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
              <th class="c-id w-90px">
                <?php
                if($canBatchImportToLib) echo "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>";
                echo common::printOrderLink('id', $orderBy, $vars, $lang->idAB);
                ?>
              </th>
              <th class="w-auto"><?php common::printOrderLink('title', $orderBy, $vars, $lang->issue->title);?></th>
              <th class="w-70px text-center" title='<?php echo $lang->issue->pri;?>'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->issue->priAB);?></th>
              <th class="w-80px"><?php common::printOrderLink('severity', $orderBy, $vars, $lang->issue->severity);?></th>
              <th class="w-80px"><?php common::printOrderLink('type', $orderBy, $vars, $lang->issue->type);?></th>
              <th class="w-100px"><?php common::printOrderLink('owner', $orderBy, $vars, $lang->issue->owner);?></th>
              <th class="w-140px"><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->issue->createdDate);?></th>
              <?php if($browseType == 'assignto'):?>
              <th class="w-100px"><?php common::printOrderLink('assignedBy', $orderBy, $vars, $lang->issue->assignedBy);?></th>
              <?php else:?>
              <th class="w-100px text-center"><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->issue->assignedTo);?></th>
              <?php endif;?>
              <th class="w-80px"><?php common::printOrderLink('status', $orderBy, $vars, $lang->issue->status);?></th>
              <th class="c-actions-8"><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php $showDividing = ((common::hasPriv('issue', 'confirm') or common::hasPriv('issue', 'resolve') or common::hasPriv('issue', 'close') or common::hasPriv('issue', 'cancel')) and (common::hasPriv('issue', 'activate') or common::hasPriv('effort', 'createForObject') or common::hasPriv('issue', 'edit')));?>
            <?php foreach($issueList as $id => $issue):?>
            <tr>
              <td class="c-id">
                <?php echo $canBatchImportToLib ? html::checkbox('issueIDList', array($issue->id => '')) . sprintf('%03d',$issue->id) : sprintf('%03d',$issue->id);?>
              </td>
              <td class="text-ellipsis" title="<?php echo $issue->title;?>">
                <?php
                if(commonModel::hasPriv('issue', 'view'))
                {
                    echo html::a(helper::createLink('issue', 'view', "id=$issue->id&from=$from"), $issue->title);
                }
                else
                {
                    echo $issue->title;
                }
                ?>
              </td>
              <td class='text-center' title="<?php echo zget($lang->issue->priList, $issue->pri, '');?>"><span class="label-pri label-pri-<?php echo $issue->pri?>"><?php echo zget($lang->issue->priList, $issue->pri, '');?></span></td>
              <td class='severity-issue severity-<?php echo $issue->severity;?>'  title="<?php echo zget($lang->issue->severityList, $issue->severity, '');?>"><?php echo zget($lang->issue->severityList, $issue->severity);?></td>
              <td title="<?php echo zget($lang->issue->typeList, $issue->type, '');?>"><?php echo zget($lang->issue->typeList, $issue->type);?></td>
              <td title="<?php echo zget($users, $issue->owner);?>"><?php echo zget($users, $issue->owner);?></td>
              <td title="<?php echo substr($issue->createdDate, 0, 10);?>"><?php echo substr($issue->createdDate, 0, 10);?></td>
              <?php
              $assignedClass = '';
              if($issue->assignedTo == $app->user->account) $assignedClass = 'assigned-current';
              if(!empty($issue->assignedTo) and $issue->assignedTo != $app->user->account) $assignedClass = 'assigned-other';
              ?>
              <?php if($browseType == 'assignto'):?>
              <td title="<?php echo zget($users, $issue->assignedBy);?>"><?php echo zget($users, $issue->assignedBy);?></td>
              <?php else:?>
              <td class="c-assignedTo <?php echo $assignedClass;?>">
              <?php echo $this->issue->printAssignedHtml($issue, $users);?>
              </td>
              <?php endif;?>
              <td class='status-issue status-<?php echo $issue->status;?>' title="<?php echo zget($lang->issue->statusList, $issue->status, '');?>"><?php echo zget($lang->issue->statusList, $issue->status);?></td>
              <td class="c-actions">
                <?php
                  $params = "issueID=$issue->id";
                  echo common::printIcon('issue', 'confirm', $params, $issue, 'list', 'ok', '', 'iframe', true, '', $lang->issue->confirm);
                  echo common::printIcon('issue', 'resolve', $params . "&from=$from", $issue, 'list', 'checked', '', 'iframe', true, '', $lang->issue->resolve);
                  echo common::printIcon('issue', 'close', $params, $issue, 'list', 'off', '', 'iframe', true);
                  echo common::printIcon('issue', 'cancel', $params, $issue, 'list', 'ban-circle', '', 'iframe', true);
                  if($showDividing) echo '<div class="dividing-line"></div>';
                  echo common::printIcon('issue', 'activate', $params . "&from=$from", $issue, 'list', 'magic', '', 'iframe', true, '', $lang->issue->activate);
                  echo common::printIcon('effort', 'createForObject', "objectType=issue&objectID=$issue->id", '', 'list', 'time', '', 'iframe', true, '', $lang->issue->effort);
                  echo common::printIcon('issue', 'edit', $params . "&from=$from", $issue, 'list', 'edit');
                ?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      <?php if($issueList):?>
      <div class='table-footer'>
        <?php if($canBatchImportToLib):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar">
          <?php echo html::a('#batchImportToLib', $lang->issue->importToLib, '', 'class="btn" data-toggle="modal" id="importToLib"');?>
          <div class="table-statistic"></div>
        </div>
        <?php endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
      </form>
    <?php else:?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->noData;?></span>
          <?php if(common::hasPriv('issue', 'create')):?>
          <?php echo html::a($this->createLink('issue', 'create', "projectID=$projectID&from=$from"), '<i class="icon icon-plus"></i> ' . $lang->issue->create, '', "class='btn btn-info' data-app='{$app->tab}'")?>
          <?php endif;?>
        </p>
      </div>
    <?php endif;?>
  </div>
</div>

<div class="modal fade" id="batchImportToLib">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->issue->importToLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('issue', 'batchImportToLib');?>'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->issue->lib;?></th>
              <td>
                <?php echo html::select('lib', $libs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approveIssue') and !common::hasPriv('assetlib', 'batchApproveIssue')):?>
            <tr>
              <th><?php echo $lang->issue->approver;?></th>
              <td>
                <?php echo html::select('assignedTo', $approvers, '', "class='form-control chosen'");?>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='2' class='text-center'>
                <?php echo html::hidden('issueIDList', '');?>
                <?php echo html::submitButton($lang->import, '', 'btn btn-primary');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
