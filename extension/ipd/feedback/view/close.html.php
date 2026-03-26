<?php
/**
 * The comment view file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     feedback
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->feedback->close;?></h2>
  </div>
  <form method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr class='<?php echo (!empty($this->app->user->feedback) or $this->cookie->feedbackView) ? 'hidden' : ''?>'>
        <th><?php echo $lang->feedback->closedReason?></th>
        <td><?php echo html::select('closedReason', $lang->feedback->closedReasonList, $closedReason, "class='form-control picker-select'");?></td>
        <td></td>
      </tr>
      <tr class='hidden'>
        <th><?php echo $lang->feedback->repeatFeedback?></th>
        <td><?php echo html::select('repeatFeedback', $feedbacks, '', "class='form-control'");?></td>
      </tr>
      <tr class='hide'>
        <th><?php echo $lang->feedback->status;?></th>
        <td><?php echo html::hidden('status', 'closed');?></td>
      </tr>
      <?php $this->printExtendFields($feedback, 'table');?>
      <tr>
        <th><?php echo $lang->comment?></th>
        <td colspan='3'><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<script>
$('#closedReason').change(function()
{
    var value  = $(this).val();
    $('#repeatFeedback').parents('tr').toggleClass('hidden', (value !== 'repeat'))
});

$('#repeatFeedback').picker(
{
    disableEmptySearch : true,
    dropWidth : 'auto',
    onReady: function(event)
    {
        $(event.picker.$container).addClass('required');
    }
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
