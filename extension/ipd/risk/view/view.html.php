<?php
/**
 * The view of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     view
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php $browseLink = $app->session->riskList ? $app->session->riskList : $this->createLink('risk', 'browse', "projectID={$risk->project}&from=$from");?>
<?php $hasRisklib = helper::hasFeature('risklib');?>
<?php js::set('sysurl', common::getSysUrl());?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $risk->id?></span>
      <span class="text" title='<?php echo $risk->name;?>'><?php echo $risk->name;?></span>
      <?php if($risk->deleted):?>
      <span class='label label-danger'><?php echo $lang->risk->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->risk->prevention;?></div>
        <div class="detail-content article-content"><?php echo $risk->prevention;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->risk->remedy;?></div>
        <div class="detail-content article-content"><?php echo $risk->remedy;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->risk->resolution;?></div>
        <div class="detail-content article-content"><?php echo $risk->resolution;?></div>
      </div>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$risk->deleted):?>
        <?php
        common::printIcon('risk', 'track', "riskID=$risk->id", $risk, "button", 'checked', '', 'iframe showinonlybody', true);
        common::printIcon('risk', 'assignTo', "riskID=$risk->id", $risk, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('effort', 'createForObject', "objectType=risk&objectID=$risk->id", '', 'button', 'time', '', 'iframe', true, '', $lang->risk->effort);
        common::printIcon('risk', 'cancel', "riskID=$risk->id", $risk, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('risk', 'close',    "riskID=$risk->id", $risk, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('risk', 'hangup',    "riskID=$risk->id", $risk, 'button', 'pause', '', 'iframe showinonlybody', true);
        common::printIcon('risk', 'activate',    "riskID=$risk->id", $risk, 'button', '', '', 'iframe showinonlybody', true);
        if(common::hasPriv('risk', 'importToLib') and $hasRisklib) echo html::a('#importToLib', "<i class='icon icon-assets'></i> " . $this->lang->risk->importToLib, '', 'class="btn" data-toggle="modal"');
        echo "<div class='divider'></div>";
        common::printIcon('risk', 'edit', "riskID=$risk->id&from=$from", $risk);
        common::printIcon('risk', 'delete', "riskID=$risk->id&from=$from", $risk, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#basicInfo' data-toggle='tab'><?php echo $lang->risk->legendBasicInfo;?></a></li>
          <li><a href='#legendLifeTime' data-toggle='tab'><?php echo $lang->risk->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->id;?></th>
                  <td><?php echo $risk->id;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->source;?></th>
                  <td><?php echo zget($lang->risk->sourceList, $risk->source);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->execution;?></th>
                  <td>
                    <?php if(isset($risk->execution) and ($risk->execution != 0)):?>
                    <?php common::printLink('execution', 'view', "executionID=$risk->execution", zget($execution, 'name'), '', 'data-app="execution"');?>
                    <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->category;?></th>
                  <td><?php echo zget($lang->risk->categoryList, $risk->category);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->strategy;?></th>
                  <td><?php echo zget($lang->risk->strategyList, $risk->strategy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->status;?></th>
                  <td><span class='status-risk status-<?php echo $risk->status?>'><?php echo $this->processStatus('risk', $risk);?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->impact;?></th>
                  <td><?php echo zget($lang->risk->impactList, $risk->impact);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->probability;?></th>
                  <td><?php echo zget($lang->risk->probabilityList, $risk->probability);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->rate;?></th>
                  <td><?php echo $risk->rate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->pri;?></th>
                  <td><span class='<?php echo 'pri-' . $risk->pri;?>' title='<?php echo zget($lang->risk->priList, $risk->pri)?>'><?php echo zget($lang->risk->priList, $risk->pri)?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->identifiedDate;?></th>
                  <td><?php echo $risk->identifiedDate == '0000-00-00' ? '' : $risk->identifiedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->plannedClosedDate;?></th>
                  <td><?php echo $risk->plannedClosedDate == '0000-00-00' ? '' : $risk->plannedClosedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->actualClosedDate;?></th>
                  <td><?php echo $risk->actualClosedDate == '0000-00-00' ? '' : $risk->actualClosedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='liftTime-assignedTo'><?php echo $lang->risk->assignedTo;?></th>
                  <td><?php echo zget($users, $risk->assignedTo);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->trackedBy;?></th>
                  <td><?php echo zget($users, $risk->trackedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->trackedDate;?></th>
                  <td><?php echo $risk->trackedDate == '0000-00-00' ? '' : $risk->trackedDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->createdBy;?></th>
                  <td><?php echo zget($users, $risk->createdBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->createdDate;?></th>
                  <td><?php echo $risk->createdDate == '0000-00-00' ? '' : $risk->createdDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->editedBy;?></th>
                  <td><?php echo zget($users, $risk->editedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->editedDate;?></th>
                  <td><?php echo $risk->editedDate == '0000-00-00' ? '' : $risk->editedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->assignedDate;?></th>
                  <td><?php echo $risk->assignedDate == '0000-00-00' ? '' : $risk->assignedDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->resolvedBy;?></th>
                  <td><?php echo zget($users, $risk->resolvedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->actualClosedDate;?></th>
                  <td><?php echo $risk->actualClosedDate == '0000-00-00' ? '' : $risk->actualClosedDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->cancelBy;?></th>
                  <td><?php echo zget($users, $risk->cancelBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->cancelDate;?></th>
                  <td><?php echo $risk->cancelDate == '0000-00-00' ? '' : $risk->cancelDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->cancelReason;?></th>
                  <td><?php echo zget($lang->risk->cancelReasonList, $risk->cancelReason);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->hangupBy;?></th>
                  <td><?php echo zget($users, $risk->hangupBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->hangupDate;?></th>
                  <td><?php echo $risk->hangupDate == '0000-00-00' ? '' : $risk->hangupDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->activateBy;?></th>
                  <td><?php echo zget($users, $risk->activateBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->activateDate;?></th>
                  <td><?php echo $risk->activateDate == '0000-00-00' ? '' : $risk->activateDate;?></td>
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
          <li class='active'><a href='#legendMisc' data-toggle='tab'><?php echo $lang->risk->legendMisc;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendMisc'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='misc-linkdedIssues'><?php echo $lang->risk->linkedIssues;?></th>
                  <td>
                    <?php
                    $hasViewIssuePriv = common::hasPriv('issue', 'view');
                    foreach($linkedIssues as $issueID => $issue)
                    {
                        $issueTitle = "#$issueID {$issue->title}";
                        $issueHtml  = $issueTitle;
                        if($hasViewIssuePriv)
                        {
                            $issueLink  = $this->createLink('issue', 'view', "issueID=$issueID", '', true);
                            $issueClass = isonlybody() ? '' : 'iframe';
                            $issueHtml  = html::a($issueLink, $issueTitle, '', "class='$issueClass' data-width='80%'");
                        }
                        $issueHtml .= '<br />';
                        echo $issueHtml;
                    }
                    ?>
                  </td>
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
        <h4 class="modal-title"><?php echo $lang->risk->importToLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('risk', 'importToLib', "risk=$risk->id");?>'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->risk->lib;?></th>
              <td>
                <?php echo html::select('lib', $libs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approveStory') and !common::hasPriv('assetlib', 'batchApproveStory')):?>
            <tr>
              <th><?php echo $lang->risk->approver;?></th>
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
  <?php common::printPreAndNext($preAndNext, $this->createLink('risk', 'view', "riskID=%s&from=$from"));?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
