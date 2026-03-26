<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $browseLink = $this->session->reviewList ? $this->session->reviewList : inlink('browse', "projectID={$review->project}");?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"')?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $review->id?></span>
      <span class="text"><?php echo $review->title . '<i class="icon-angle-right"></i> ' . $lang->review->report->common?></span>
    </div>
    <div class='input-control space w-150px'>
      <?php
      $n = 0;
      foreach($approvalIDList as &$approvalItem)
      {
          $n++;
          $approvalItem = sprintf($lang->review->selectApprovalText, $n);
      }
      ?>
      <?php echo html::select('approval', $approvalIDList, $approvalID, "onchange='changeReview($reviewID, this.value)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<div class='main-row' id='mainContent'>
  <div class='main-col main-table'>
    <div class='cell'>
      <table class='table table-borderless'>
        <tr><th class='text-center' colspan='8' style="font-size:15px;"><?php echo $lang->review->explain;?></th></tr>
        <tr>
          <th colspan='2'><?php echo $lang->review->object;?></th>
          <td colspan='2'><?php echo zget($lang->baseline->objectList, $review->category);?></td>
          <th colspan='2'><?php echo $lang->review->reviewerCount;?></th>
          <td colspan='2'><?php echo $reviewerCount;?></td>
        </tr>
        <tr>
          <th colspan='2'><?php echo $lang->review->reviewedDate;?></th>
          <td colspan='2'><?php echo isset($approvalNode[0]->date) and !helper::isZeroDate($approvalNode[0]->date) ? $approvalNode[0]->date : '';?></td>
          <th colspan='2'><?php echo $lang->review->reviewedHours;?></th>
          <td colspan='2'>
          <?php
          $objectScale = (float)$objectScale;
          $consumed    = 0;
          $accountConsumed = array();
          unset($efforts['typeList']);
          foreach($efforts as $effort)
          {
              $accountConsumed[$effort->account][] = $effort->consumed;
              $consumed += empty($effort->consumed) ? 0 : $effort->consumed;
          }
          echo floor($consumed);
          ?>
          </td>
        </tr>
        <tr>
          <th colspan='2'>
            <div class='th-flex'>
              <div><?php echo $lang->review->issueCount;?></div>
              <div class='icon-align-center'><a data-toggle='tooltip' class='text-help' title='<?php echo $lang->review->issueCountTip;?>'><i class='icon-help'></i></a></div>
            </div>
          </th>
          <td colspan='2'><?php echo count($issues);?></td>
          <th colspan='2'>
            <div class='th-flex'>
              <div><?php echo $lang->review->objectScale;?></div>
              <div class='icon-align-center'><a data-toggle='tooltip' class='text-help' title='<?php echo $lang->review->objectScaleTip;?>'><i class='icon-help'></i></a></div>
            </div>
          </th>
          <td colspan='2'><?php echo round($objectScale, 2);?></td>
        </tr>
        <tr>
          <th colspan='2'>
            <div class='th-flex'>
              <div><?php echo $lang->review->issueRate;?></div>
              <div class='icon-align-center'><a data-toggle='tooltip' class='text-help' title='<?php echo $lang->review->issueRateTip;?>'><i class='icon-help'></i></a></div>
            </div>
          </th>
          <td colspan='2'><?php echo $objectScale == 0 ? 0 : round(count($issues) / $objectScale, 2);?></td>
          <th colspan='2'>
            <div class='th-flex'>
              <div><?php echo $lang->review->issueFoundRate;?></div>
              <div class='icon-align-center'><a data-toggle='tooltip' class='text-help' title='<?php echo $lang->review->issueFoundRateTip;?>'><i class='icon-help'></i></a></div>
            </div>
          </th>
          <td colspan='2'><?php echo $consumed == 0 ? 0 : round(count($issues) / $consumed, 2);?></td>
        </tr>
      </table>
    </div>
    <div class='cell'>
    <table class='table table-borderless'>
    <?php if(!empty($issues)):?>
        <tr>
          <th><?php echo $lang->idAB;?></th>
          <th colspan='2'><?php echo $lang->review->issues;?></th>
          <th colspan='2'><?php echo $lang->review->reviewer;?></th>
          <th colspan='5'><?php echo $lang->comment;?></th>
        </tr>
        <?php foreach($issues as $issue):?>
        <tr>
          <td><?php echo $issue->id;?></td>
          <td colspan='2'><?php echo $issue->title;?></td>
          <td colspan='2'><?php echo zget($users, $issue->createdBy);?></td>
          <td colspan='5' class="text-ellipsis" title='<?php echo strip_tags($issue->opinion);?>'><?php echo $issue->opinion;?></td>
        </tr>
        <?php endforeach;?>
    <?php endif;?>
    <?php if(!empty($approvalNode)):?>
      <table class='table table-borderless'>
        <tr>
          <th><?php echo $lang->review->reviewedDate;?></th>
          <th colspan='2'><?php echo $lang->review->reviewer;?></th>
          <th><?php echo $lang->review->reviewResult;?></th>
          <th><?php echo $lang->reviewresult->consumed;?></th>
          <th colspan='5'><?php echo $lang->review->finalOpinion;?></th>
        </tr>
        <?php foreach($approvalNode as $reviewItem):?>
        <?php if(empty($reviewItem->reviewedBy) or empty($accountConsumed[$reviewItem->reviewedBy])) continue;?>
        <tr>
          <td><?php echo !helper::isZeroDate($reviewItem->date) ? $reviewItem->date : '';?></td>
          <td colspan='2'><?php echo zget($users, $reviewItem->reviewedBy);?></td>
          <td><?php echo zget($lang->review->resultList, $reviewItem->result);?></td>
          <td><?php echo (!empty($accountConsumed[$reviewItem->reviewedBy]) and $reviewItem->result != 'ignore') ? array_shift($accountConsumed[$reviewItem->reviewedBy]) : 0;?></td>
          <td colspan='5' class="text-ellipsis finalOpinion" title=<?php echo strip_tags($reviewItem->opinion);?>><?php echo $reviewItem->opinion;?></td>
        </tr>
        <?php endforeach;?>
    <?php endif;?>
        <tr id="resultExplain">
          <th class='text-center' colspan='8'><?php echo $lang->review->resultExplain;?></th>
          <th class='text-center' colspan='2'><?php echo $lang->review->conclusion;?></th>
        </tr>
        <tr>
          <td colspan='8'><?php echo $lang->review->resultExplainList['pass'];?></td>
          <?php if(isset($approval->result)):?>
          <td rowspan='2' colspan='2' class="text-center status-<?php echo $approval->result;?>" style='background: #e3f2fd;'><?php echo zget($lang->review->resultList, $approval->result);?></td>
          <?php else:?>
          <?php $reviewResult = $review->result == 'pass' ? 'pass' : 'fail';?>
          <td rowspan='2' colspan='2' class="text-center status-<?php echo $reviewResult;?>" style='background: #e3f2fd;'><?php echo zget($lang->review->resultList, $reviewResult);?></td>
          <?php endif;?>
        </tr>
        <tr>
          <td colspan='8'><?php echo $lang->review->resultExplainList['fail'];?></td>
        </tr>
        <tr>
          <th colspan='2'><?php echo $lang->review->reportCreatedBy;?></th>
          <th colspan='2'><?php echo zget($users, $review->createdBy);?></th>
          <th colspan='2'><?php echo $lang->review->reportApprovedBy;?></th>
          <th colspan='2'>
            <?php
            foreach($reviewer as $account)
            {
                $account = trim($account);
                if(empty($account)) continue;
                echo zget($users, $account) . " &nbsp;";
            }
            ?>
          </th>
        </tr>
      </table>
    </div>
  </div>
</div>

<script>
function changeReview(reviewID, approvalID)
{
    location.href = createLink('review', 'report', 'reviewID=' + reviewID + '&approvalID=' + approvalID);
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
