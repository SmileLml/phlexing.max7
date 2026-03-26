<?php include $app->getModuleRoot() . 'common/view/header.html.php'?>
<?php $browseLink = inlink('issue', "project=$projectID&reviewID=$issue->reviewID");?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::backButton('<i class="icon icon-back icon-sm"></i>' . $lang->goback , '','btn btn-secondary');?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $issueID;?></span>
      <span class="text"><?php echo $issue->title;?></span>
      <?php if($issue->deleted):?>
      <span class='label label-danger'><?php echo $lang->reviewissue->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class='cell'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <?php
    $params = $issue->status == 'resolved' ? "issueID=$issueID&status=active" : "issueID=$issueID&status=resolved";
    ?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php echo html::backButton('<i class="icon icon-back icon-sm"></i>' . $lang->goback , '','btn btn-secondary');?>
        <?php if(!$issue->deleted):?>
        <div class='divider'></div>
        <?php
          js::set('confirmActive', $lang->reviewissue->confirmActive);
          js::set('confirmClose', $lang->reviewissue->confirmClose);
          $issueParams = ($issue->status == 'resolved' || $issue->status == 'closed') ? "issueID=$issueID&status=active" : "project=$projectID&issueID=$issueID&status=resolved";
          $updateURL   = ($issue->status == 'resolved' || $issue->status == 'closed') ? $this->createLink('reviewissue', 'updateStatus', $issueParams) : $this->createLink('reviewissue', 'resolved', $issueParams, '', true);
          $closedURL   = $this->createLink('reviewissue', 'updateStatus', "issueID=$issueID&status=closed");
          if($issue->status == 'resolved' || $issue->status == 'closed') echo html::a("javascript:ajaxDelete(\"$updateURL\", \"getList\", confirmActive)", '<i class="icon-bug-activate icon-magic"></i><span class="text">' . $lang->reviewissue->activation . '</span>', '', "class='btn btn-link'");
          if($issue->status == 'active') echo html::a($updateURL, '<i class="icon-checked"></i>' . $lang->reviewissue->resolved, '', 'class="btn iframe " data-size="sm"');
          if($issue->status == 'resolved') echo html::a("javascript:ajaxDelete(\"$closedURL\", \"getList\", confirmClose)", '<i class="icon-bug-activate icon-off"></i>' . "<span class='text'>{$lang->reviewissue->close}</span>", '', "title='{$lang->reviewissue->close}' class='btn '");
        ?>
        <div class='divider'></div>
        <?php common::printIcon('reviewissue', 'delete', "issueID=$issue->id&project=$projectID&confirm=no", $issue, 'button', 'trash', 'hiddenwin');?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->reviewissue->issueInfo;?></div>
        <div class="detail-content">
          <table class="table table-data">
            <tbody>
              <tr>
                <th class="w-100px"><?php echo $lang->reviewissue->review;?></th>
                <td><?php echo html::a($this->createLink('review', 'view', "reviewID=$issue->review"), $issue->reviewTitle)?></td>
              </tr>
              <tr>
                <th class="w-100px"><?php echo $lang->reviewissue->title;?></th>
                <td><?php echo $issue->title;?></td>
              </tr>
              <tr>
                <th class="w-100px"><?php echo $lang->reviewissue->opinion;?></th>
                <td><?php echo $issue->opinion;?></td>
              </tr>
              <tr>
                <th class="w-100px"><?php echo $lang->reviewissue->status;?></th>
                <td><?php echo zget($lang->reviewissue->statusList, $issue->status);?></td>
              </tr
              ><tr>
                <th class="w-100px"><?php echo $lang->reviewissue->createdBy;?></th>
                <td><?php echo zget($users, $issue->createdBy);?></td>
              </tr>
              <tr>
                <th class="w-100px"><?php echo $lang->reviewissue->createdDate;?></th>
                <td><?php echo $issue->createdDate;?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php'?>
