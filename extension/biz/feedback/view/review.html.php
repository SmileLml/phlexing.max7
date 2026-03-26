<?php
/**
 * The view file of review method of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     feedback
 * @version     $Id: review.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $feedback->id;?></span>
      <?php echo html::a($this->createLink('feedback', 'view', "feedbackID=$feedback->id"), $feedback->title);?>
    </h2>
  </div>
  <form method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->feedback->reviewedDateAB;?></th>
        <td class='w-p25-f'><?php echo html::input('reviewedDate', helper::today(), "class='form-control form-date'");?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->feedback->reviewResultAB;?></th>
        <td class='required'><?php echo html::select('result', $lang->feedback->reviewResultList, '', "class='form-control'");?></td><td></td>
      </tr>
      <tr id='assignedToBox' class='hide'>
        <th><?php echo $lang->feedback->assignedTo;?></th>
        <td><?php echo html::select('assignedTo', $users, '', "class='form-control chosen'");?></td>
      </tr>
      <tr class='hide'>
        <th><?php echo $lang->feedback->status;?></th>
        <td><?php echo html::hidden('status', $feedback->status);?></td>
      </tr>
      <?php $this->printExtendFields($feedback, 'table');?>
      <tr>
        <th><?php echo $lang->feedback->reviewedByAB;?></th>
        <td colspan='2'><?php echo html::select('reviewedBy[]', $users, $app->user->account, "class='form-control chosen' multiple");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->feedback->reviewOpinion;?></th>
        <td colspan='2'><?php echo html::textarea('comment', '', "rows='8' class='form-control'");?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
  <hr class='small' />
  <div class='main'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
</div>
<?php js::set('openedBy', $feedback->openedBy);?>
<?php js::set('assignedTo', $assignedTo ? $assignedTo : $feedback->assignedTo);?>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
