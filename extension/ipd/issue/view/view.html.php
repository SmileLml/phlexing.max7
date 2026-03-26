<?php
/**
 * The details view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id: view.html.php 4488 2013-02-27 02:54:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php
$browseLink = $app->session->issueList ? $app->session->issueList : $this->createLink('issue', 'browse', "projectID={$issue->project}&from=$from");
$createLink = $this->createLink('issue', 'create', "projectID={$issue->project}&from=$from");
$dateFiled  = array('deadline', 'resolvedDate', 'createdDate', 'editedDate', 'activateDate', 'closedDate', 'assignedDate');
foreach($issue as $field => $value)
{
    if(in_array($field, $dateFiled) && strpos($value, '0000') === 0) $issue->$field = '';
}
$hasIssuelib = helper::hasFeature('issuelib');
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $issue->id?></span>
      <span class="text" title="<?php echo $issue->title?>"><?php echo $issue->title?></span>
      <?php if($issue->deleted):?>
      <span class='label label-danger'><?php echo $lang->issue->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
  <?php if(!isonlybody()):?>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('issue', 'create')) echo html::a($createLink, "<i class='icon icon-plus'></i> {$lang->issue->create}", '', "class='btn btn-primary' data-app='{$app->tab}'");?>
  </div>
  <?php endif;?>
</div>
<div class="main-row" id="mainContent">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->issue->desc;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($issue->desc) ? $issue->desc : '<div class="text-center text-muted">' . $lang->noData . '</div>';?>
        </div>
      </div>
      <?php if($issue->files):?>
      <div class="detail"><?php echo $this->fetch('file', 'printFiles', array('files' => $issue->files, 'fieldset' => 'true'));?></div>
      <?php endif;?>
    </div>
    <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=issue&objectID=$issue->id");?>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$issue->deleted):?>
        <?php
          $params = "issueID=$issue->id";
          common::printIcon('issue', 'confirm', $params, $issue, 'button', 'ok', '', 'iframe showinonlybody', true);
          common::printIcon('issue', 'resolve', $params, $issue, 'button', 'checked', '', 'iframe showinonlybody', true);
          common::printIcon('issue', 'assignTo', $params, $issue, 'button', '', '', 'iframe showinonlybody', true);
          common::printIcon('effort', 'createForObject', "objectType=issue&objectID=$issue->id", '', 'button', 'time', '', 'iframe', true, '', $lang->issue->effort);
          common::printIcon('issue', 'cancel', $params, $issue, 'button', '', '', 'iframe showinonlybody', true);
          common::printIcon('issue', 'close', $params, $issue, 'button', '', '', 'iframe showinonlybody', true);
          common::printIcon('issue', 'activate', $params, $issue, 'button', '', '', 'iframe showinonlybody', true);
          if(common::hasPriv('issue', 'importToLib') and $hasIssuelib) echo html::a('#importToLib', "<i class='icon icon-assets'></i> " . $this->lang->issue->importToLib, '', 'class="btn" data-toggle="modal"');
          echo "<div class='divider'></div>";
          common::printIcon('issue', 'edit', $params . "&from=$from", $issue);
          common::printIcon('issue', 'delete', $params . "&from=$from", $issue, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#basicInfo' data-toggle='tab'><?php echo $lang->issue->basicInfo;?></a></li>
          <li><a href='#lifeTime' data-toggle='tab'><?php echo $lang->issue->lifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class="w-100px"><?php echo $lang->issue->type;?></th>
                  <td><?php echo zget($lang->issue->typeList, $issue->type);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->severity;?></th>
                  <td class='severity-issue severity-<?php echo $issue->severity;?>'><?php echo zget($lang->issue->severityList, $issue->severity);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->execution;?></th>
                  <td><?php if(!empty($execution) && !common::printLink('execution', 'view', "executionID=$issue->execution", $execution->name, '', 'data-app=execution')) echo $execution->name;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->pri;?></th>
                  <td><span class="label-pri label-pri-<?php echo $issue->pri?>"><?php echo zget($lang->issue->priList, $issue->pri, '');?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->deadline;?></th>
                  <td><?php echo $issue->deadline;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->resolvedDate;?></th>
                  <td><?php echo $issue->resolvedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->owner;?></th>
                  <td><?php echo zget($users, $issue->owner);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->assignedTo;?></th>
                  <td><?php echo $issue->assignedTo ? zget($users, $issue->assignedTo) . $lang->at . $issue->assignedDate : $lang->noData;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->status;?></th>
                  <td class='status-<?php echo $issue->status;?>'><?php echo zget($lang->issue->statusList, $issue->status);?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='lifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class="w-100px"><?php echo $lang->issue->createdBy;?></th>
                  <td><?php echo zget($users, $issue->createdBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->createdDate;?></th>
                  <td><?php echo $issue->createdDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->assignedBy;?></th>
                  <td><?php echo zget($users, $issue->assignedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->assignedDate;?></th>
                  <td><?php echo $issue->assignedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->closedBy;?></th>
                  <td><?php echo zget($users, $issue->closedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->closedDate;?></th>
                  <td><?php echo $issue->closedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->activateBy;?></th>
                  <td><?php echo zget($users, $issue->activateBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->activateDate;?></th>
                  <td><?php echo $issue->activateDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->editedBy;?></th>
                  <td><?php echo zget($users, $issue->editedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->issue->editedDate;?></th>
                  <td><?php echo $issue->editedDate;?></td>
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
          <li class='active'><a href='#legendMisc' data-toggle='tab'><?php echo $lang->issue->legendMisc;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendMisc'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->issue->linkedRisk;?></th>
                  <td>
                    <?php
                    if($linkedRisk)
                    {
                        $hasViewRiskPriv = common::hasPriv('risk', 'view');
                        $riskTitle = "#{$linkedRisk->id} {$linkedRisk->name}";
                        $riskHtml  = $riskTitle;
                        if($hasViewRiskPriv)
                        {
                            $riskLink  = $this->createLink('risk', 'view', "riskID=$linkedRisk->id", '', true);
                            $riskClass = isonlybody() ? '' : 'iframe';
                            $riskHtml  = html::a($riskLink, $riskTitle, '', "class='$riskClass' data-width='80%'");
                        }
                        echo $riskHtml;
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
        <h4 class="modal-title"><?php echo $lang->issue->importToLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('issue', 'importToLib', "issue=$issue->id");?>'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->issue->lib;?></th>
              <td>
                <?php echo html::select('lib', $libs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approveStory') and !common::hasPriv('assetlib', 'batchApproveStory')):?>
            <tr>
              <th><?php echo $lang->issue->approver;?></th>
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
  <?php common::printPreAndNext($preAndNext, $this->createLink('issue', 'view', "issueID=%s&from=$from"));?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
