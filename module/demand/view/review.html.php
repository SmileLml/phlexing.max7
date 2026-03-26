<?php
/**
 * The view file of review method of demand module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     demand
 * @version     $Id: review.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div class='main-content' id='mainContent'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $demand->id;?></span>
        <?php echo html::a($this->createLink('demand', 'view', "demandID=$demand->id"), $demand->title);?>
        <small><?php echo $lang->arrow . $lang->demand->review;?></small>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->demand->reviewedDate;?></th>
          <td class='w-p25-f'><?php echo html::input('reviewedDate', helper::now(), "class='form-control form-datetime' data-picker-position='bottom-right'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->demand->reviewResult;?></th>
          <td class='required'><?php echo html::select('result', $lang->demand->resultList, '', 'class="form-control chosen" onchange="switchShow(this.value)"');?></td><td></td>
        </tr>
        <tr id='assignedToBox' <?php echo $isLastOne ? '' : "class='hide'";?>>
          <th><?php echo $lang->demand->assignedTo;?></th>
          <td><?php echo html::select('assignedTo', $assignToList, $demand->assignedTo, "class='form-control picker-select'");?></td><td></td>
        </tr>
        <tr id='rejectedReasonBox' style="display:none">
          <th><?php echo $lang->story->rejectedReason;?></th>
          <td class='required'><?php echo html::select('closedReason', $lang->demand->reasonList, '', 'class=form-control onchange="setDemand(this.value)"');?></td><td></td>
        </tr>
        <tr id='priBox' class='hide'>
          <th><?php echo $lang->demand->pri;?></th>
          <td><?php echo html::select('pri', $lang->demand->priList, $demand->pri,"class='form-control'");?></td><td></td>
        </tr>
        <tr id='duplicateDemandBox' class='hide'>
          <th><?php echo $lang->demand->duplicateDemand;?></th>
          <td><?php echo html::input('duplicateDemand', '', "class='form-control'");?></td><td></td>
        </tr>
        <tr id='childStoriesBox' class='hide'>
          <th><?php echo $lang->demand->childStories;?></th>
          <td><?php echo html::input('childStories', '', "class='form-control'");?></td><td></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->demand->status;?></th>
          <td><?php echo html::hidden('status', $demand->status);?></td>
        </tr>
        <?php $this->printExtendFields($demand, 'table');?>
        <tr>
          <th><?php echo $lang->demand->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='8' class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
          <?php if(!isonlybody()) echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
  </div>
</div>
<?php js::set('demandID', $demand->id);?>
<?php js::set('isMultiple', count($reviewers) == 1 ? false : true);?>
<?php js::set('isLastOne', $isLastOne);?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
