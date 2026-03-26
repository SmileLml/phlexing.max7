<?php
/**
 * The batch close view of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     feedback
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('app', $app->tab);?>
<div class='main-content' id='mainContent'>
  <div class='main-header'>
    <h2><?php echo $lang->feedback->common  . $lang->hyphen . $lang->feedback->batchClose;?></h2>
  </div>
  <form method='post' target='hiddenwin' id='feedbackBatchClose' action="<?php echo inLink('batchClose', "from=feedbackBatchClose")?>">
    <table class='table table-fixed table-form with-border'>
    <thead>
      <tr class='text-center'>
        <th class='c-id'><?php echo $lang->idAB;?></th>
        <th class='text-left'><?php echo $lang->feedback->title;?></th>
        <th class='c-status'><?php echo $lang->feedback->status;?></th>
        <th class='c-reason'><?php echo $lang->feedback->closedReason;?></th>
        <th class='w-p30'><?php echo $lang->feedback->commentAB;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($feedbacks as $feedbackID => $feedback):?>
      <tr class='text-center'>
        <td><?php echo $feedbackID . html::hidden("feedbackIdList[$feedbackID]", $feedbackID);?></td>
        <td title='<?php echo $feedback->title;?>' class='text-left'><?php echo $feedback->title;?></td>
        <td class='feedback-<?php echo $feedback->status;?>'><?php echo $this->processStatus('feedback', $feedback);?></td>
        <td class='reasons-td'>
          <table class='w-p100 table-form'>
            <tr>
              <td class='pd-0'>
                <?php echo html::select("closedReasons[$feedbackID]", $reasonList, 'commented', "class=form-control onchange=setDuplicate(this.value,$feedbackID) style='min-width: 80px'");?>
              </td>
              <td class='pd-0 w-p60 text-left' id='<?php echo 'duplicateFeedbackBox' . $feedbackID;?>' <?php if($feedback->closedReason != 'repeat') echo "style='display:none'";?>>
                <?php echo html::select("repeatFeedbackIDList[$feedbackID]", $feedbackList, $feedback->repeatFeedback ? $feedback->repeatFeedback : '', "class='form-control' placeholder='{$lang->bug->duplicateTip}'");?>
              </td>
            </tr>
          </table>
        </td>
        <td><?php echo html::input("comments[$feedbackID]", '', "class='form-control'");?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
       <tr>
         <td colspan='5' class='text-center form-actions'>
           <?php echo html::submitButton();?>
           <?php echo html::backButton();?>
         </td>
       </tr>
    </tfoot>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
