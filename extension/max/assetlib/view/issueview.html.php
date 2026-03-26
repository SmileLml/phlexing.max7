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
$dateFiled  = array('createdDate', 'editedDate', 'approvedDate');
foreach($issue as $field => $value)
{
    if(in_array($field, $dateFiled) && strpos($value, '0000') === 0) $issue->$field = '';
}
$canIssueView = (common::hasPriv('issue', 'view') and helper::hasFeature('issue'));
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
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
          common::printIcon('assetlib', 'editIssue', $params, $issue, 'button', 'edit');
          if($issue->status == 'draft') common::printIcon('assetlib', 'approveIssue', "issueID=$issue->id", $issue, 'button', 'glasses', '', 'iframe showinonlybody', true);
          common::printIcon('assetlib', 'removeIssue', $params, $issue, 'button', 'unlink', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <details class="detail" open="">
      <summary class="detail-title"><?php echo $lang->issue->basicInfo;?></summary>
      <div class="detail-content">
        <table class="table table-data">
          <tbody>
            <?php $widthClass = common::checkNotCN() ? 'w-100px' : 'w-80px';?>
            <tr>
              <th class='<?php echo $widthClass;?>'><?php echo $lang->assetlib->sourceIssue;?></th>
              <td>
                <?php if($canIssueView):?>
                <?php echo html::a($this->createLink('issue', 'view', "issueID={$issue->from}"), $issue->sourceName);?>
                <?php else:?>
                <?php echo $issue->sourceName;?>
                <?php endif;?>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->issue->type;?></th>
              <td><?php echo zget($lang->issue->typeList, $issue->type);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->issue->severity;?></th>
              <td><?php echo zget($lang->issue->severityList, $issue->severity);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->issue->pri;?></th>
              <td><span class="label-pri label-pri-<?php echo $issue->pri?>"><?php echo zget($lang->issue->priList, $issue->pri);?></span></td>
            </tr>
            <tr>
              <th><?php echo $lang->assetlib->status;?></th>
              <td><span class='status-issue status-<?php echo $issue->status?>'><span class="label label-dot"></span> <?php echo zget($lang->assetlib->statusList, $issue->status);?></span></td>
            </tr>
            <tr>
              <th><?php echo $lang->assetlib->importedBy;?></th>
              <td><?php echo zget($users, $issue->createdBy);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->assetlib->importedDate;?></th>
              <td><?php echo $issue->createdDate;?></td>
            </tr>
            <?php if($issue->status == 'active'):?>
            <tr>
              <th><?php echo $lang->assetlib->approvedBy;?></th>
              <td><?php echo zget($users, $issue->assignedTo);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->assetlib->approvedDate;?></th>
              <td><?php echo $issue->approvedDate;?></td>
            </tr>
            <?php endif;?>
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
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
