<?php
/**
 * The view of opportunity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     view
 * @version     $Id: view.html.php 4903 2021-05-26 11:00:59Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php $browseLink        = $app->session->opportunityList ? $app->session->opportunityList : $this->createLink('opportunity', 'browse', "projectID={$opportunity->project}");?>
<?php $hasOpportunitylib = helper::hasFeature('opportunitylib');?>
<?php js::set('sysurl', common::getSysUrl());?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary' data-app='{$app->tab}'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $opportunity->id?></span>
      <span class="text" title='<?php echo $opportunity->name;?>'><?php echo $opportunity->name;?></span>
      <?php if($opportunity->deleted):?>
      <span class='label label-danger'><?php echo $lang->opportunity->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->opportunity->desc;?></div>
        <div class="detail-content article-content"><?php echo $opportunity->desc;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->opportunity->prevention;?></div>
        <div class="detail-content article-content"><?php echo $opportunity->prevention;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->opportunity->resolution;?></div>
        <div class="detail-content article-content"><?php echo $opportunity->resolution;?></div>
      </div>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink . "#app={$app->tab}");?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php $canModify = !$opportunity->deleted;
              if($canModify && $project) $canModify = common::canModify('project', $project);
              if($canModify && $execution) $canModify = common::canModify('execution', $execution);
        ?>
        <?php if($canModify):?>
        <?php
        common::printIcon('opportunity', 'track',    "opportunityID=$opportunity->id", $opportunity, "button", 'checked', '', 'iframe showinonlybody', true);
        common::printIcon('opportunity', 'assignTo', "opportunityID=$opportunity->id", $opportunity, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('opportunity', 'cancel',   "opportunityID=$opportunity->id", $opportunity, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('opportunity', 'close',    "opportunityID=$opportunity->id", $opportunity, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('opportunity', 'hangup',   "opportunityID=$opportunity->id", $opportunity, 'button', 'arrow-up', '', 'iframe showinonlybody', true);
        common::printIcon('opportunity', 'activate', "opportunityID=$opportunity->id", $opportunity, 'button', '', '', 'iframe showinonlybody', true);
        if(common::hasPriv('opportunity', 'importToLib') and $hasOpportunitylib) echo html::a('#importToLib', "<i class='icon icon-assets'></i> " . $this->lang->opportunity->importToLib, '', 'class="btn" data-toggle="modal"');
        echo "<div class='divider'></div>";
        common::printIcon('opportunity', 'edit',   "opportunityID=$opportunity->id&from=$from", $opportunity);
        common::printIcon('opportunity', 'delete', "opportunityID=$opportunity->id&from=$from", $opportunity, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->opportunity->legendBasicInfo;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th><?php echo $lang->opportunity->source;?></th>
                  <td><?php echo zget($lang->opportunity->sourceList, $opportunity->source);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->type;?></th>
                  <td><?php echo zget($lang->opportunity->typeList, $opportunity->type);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->strategy;?></th>
                  <td><?php echo zget($lang->opportunity->strategyList, $opportunity->strategy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->status;?></th>
                  <td><span class='status-task status-<?php echo $config->opportunity->labelDot[$opportunity->status];?>'><span class="label label-dot"></span> <?php echo $this->processStatus('opportunity', $opportunity);?></span></td>
                </tr>
                <tr <?php if(empty($project->multiple)) echo "class='hidden'";?>>
                  <th><?php echo $lang->opportunity->execution;?></th>
                  <td><?php if($opportunity->execution) common::printLink('execution', 'view', "executionID=$opportunity->execution", zget($execution, 'name'), '', 'data-app="execution"');?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->impact;?></th>
                  <td><?php echo zget($lang->opportunity->impactList, $opportunity->impact);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->chance;?></th>
                  <td><?php echo zget($lang->opportunity->chanceList, $opportunity->chance);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->ratio;?></th>
                  <td><?php echo $opportunity->ratio;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->pri;?></th>
                  <td><span class='<?php echo 'pri-' . $opportunity->pri;?>' title='<?php echo zget($lang->opportunity->priList, $opportunity->pri)?>'><?php echo zget($lang->opportunity->priList, $opportunity->pri)?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->identifiedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->identifiedDate) ? '' : $opportunity->identifiedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->plannedClosedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->plannedClosedDate) ? '' : $opportunity->plannedClosedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->actualClosedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->actualClosedDate) ? '' : $opportunity->actualClosedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->opportunity->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->opportunity->assignedTo;?></th>
                  <td><?php echo zget($users, $opportunity->assignedTo);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->opportunity->lastCheckedBy;?></th>
                  <td><?php echo zget($users, $opportunity->lastCheckedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->lastCheckedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->lastCheckedDate) ? '' : $opportunity->lastCheckedDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->opportunity->createdBy;?></th>
                  <td><?php echo zget($users, $opportunity->createdBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->createdDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->createdDate) ? '' : $opportunity->createdDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->opportunity->editedBy;?></th>
                  <td><?php echo zget($users, $opportunity->editedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->editedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->editedDate) ? '' : $opportunity->editedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->assignedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->assignedDate) ? '' : $opportunity->assignedDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->opportunity->resolvedBy;?></th>
                  <td><?php echo zget($users, $opportunity->resolvedBy);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->opportunity->resolvedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->resolvedDate) ? '' : $opportunity->resolvedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->actualClosedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->actualClosedDate) ? '' : $opportunity->actualClosedDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->opportunity->canceledBy;?></th>
                  <td><?php echo zget($users, $opportunity->canceledBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->canceledDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->canceledDate) ? '' : $opportunity->canceledDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->cancelReason;?></th>
                  <td><?php echo zget($lang->opportunity->cancelReasonList, $opportunity->cancelReason);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->opportunity->hangupedBy;?></th>
                  <td><?php echo zget($users, $opportunity->hangupedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->hangupedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->hangupedDate) ? '' : $opportunity->hangupedDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->opportunity->activatedBy;?></th>
                  <td><?php echo zget($users, $opportunity->activatedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->activatedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->activatedDate) ? '' : $opportunity->activatedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="importToLib">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->opportunity->importToLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('opportunity', 'importToLib', "opportunity=$opportunity->id");?>'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->opportunity->lib;?></th>
              <td>
                <?php echo html::select('lib', $libs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approveStory') and !common::hasPriv('assetlib', 'batchApproveStory')):?>
            <tr>
              <th><?php echo $lang->opportunity->approver;?></th>
              <td>
                <?php echo html::select('assignedTo', $approvers, '', "class='form-control chosen'");?>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='2' class='text-center'>
                <?php echo html::submitButton($lang->import, '', 'btn btn-primary');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext, $this->createLink('opportunity', 'view', "opportunityID=%s&from=$from"));?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
