<?php
/**
 * The detail view file of leave module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     leave
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<?php include '../../../common/view/header.modal.html.php';?>
<table class='table table-bordered leaveTable'>
  <tr>
    <th><?php echo $lang->leave->status;?></th>
    <td class='leave-<?php echo $leave->status;?>'><?php echo $lang->leave->statusList[$leave->status];?></td>
    <th><?php echo $lang->leave->type;?></th>
    <td><?php echo zget($lang->leave->typeList, $leave->type);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->leave->start;?></th>
    <td><?php echo formatTime($leave->begin . ' ' . $leave->start, DT_DATETIME2);?></td>
    <th><?php echo $lang->leave->finish;?></th>
    <td><?php echo formatTime($leave->end . ' ' . $leave->finish, DT_DATETIME2);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->leave->hours;?></th>
    <td><?php echo $leave->hours . $lang->leave->hoursTip;?></td>
    <th><?php echo $lang->leave->backDate;?></th>
    <td><?php echo formatTime($leave->backDate, DT_DATETIME2);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->leave->desc;?></th>
    <td colspan='3'><?php echo $leave->desc;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->leave->createdBy;?></th>
    <td><?php echo zget($users, $leave->createdBy);?></th>
    <th><?php echo $lang->leave->reviewedBy;?></th>
    <td id='reviewedBy'><?php echo zget($users, $leave->reviewedBy);?></th>
  </tr>
  <tr>
    <th><?php echo $lang->leave->createdDate;?></th>
    <td><?php echo formatTime($leave->createdDate, DT_DATETIME1);?></td>
    <th><?php echo $lang->leave->reviewedDate;?></th>
    <td><?php echo formatTime($leave->reviewedDate, DT_DATETIME1);?></td>
  </tr>
</table>
<?php echo $this->fetch('action', 'history', "objectType=leave&objectID=$leave->id");?>
<div class='page-actions'>
  <?php
  if($type == 'personal')
  {
      $canBack   = $this->leave->isClickable($leave, 'back');
      $canEdit   = $this->leave->isClickable($leave, 'edit');
      $canDelete = $this->leave->isClickable($leave, 'delete');
      $canSwitch = $this->leave->isClickable($leave, 'switchStatus');

      if($canBack)
      {
          extCommonModel::printLink('oa.leave', 'back', "id={$leave->id}", $lang->leave->back, "class='btn loadInModal'");
      }
      if($canSwitch or $canEdit or $canDelete)
      {
          if($canSwitch)
          {
              $switchLabel = $leave->status == 'wait' ? $lang->leave->cancel : $lang->leave->submit;
              extCommonModel::printLink('oa.leave', 'switchstatus', "id={$leave->id}", $switchLabel, "class='btn'");
          }
          echo "<div class='btn-group'>";
          if($canEdit) extCommonModel::printLink('oa.leave', 'edit',   "id={$leave->id}", $lang->edit,   "class='btn loadInModal'");
          if($canDelete) extCommonModel::printLink('oa.leave', 'delete', "id={$leave->id}", $lang->delete, "class='btn deleteLeave'");
          echo '</div>';
      }
  }
  else
  {
      $canReview = $this->leave->isClickable($leave, 'review');

      if($canReview)
      {
          $params = $leave->status == 'back' ? '&mode=back' : '';

          echo "<div class='btn-group'>";
          extCommonModel::printLink('oa.leave', 'review', "id={$leave->id}&status=pass",   $lang->leave->statusList['pass'],   "class='btn reviewPass'");
          extCommonModel::printLink('oa.leave', 'review', "id={$leave->id}&status=reject", $lang->leave->statusList['reject'], "class='btn loadInModal'");
          echo '</div>';
      }
  }
  if(!isonlybody()) echo baseHTML::a('###', $lang->goback, "class='btn' data-dismiss='modal'");
  ?>
</div>
<?php include '../../../common/view/footer.modal.html.php';?>
