<?php
/**
 * The comment view file of ticket module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     ticket
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->ticket->close;?></h2>
  </div>
  <form method='post' enctype='multipart/form-data' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->ticket->closedReason?></th>
        <td><?php echo html::select('closedReason', $lang->ticket->closedReasonList, '', "class='form-control picker-select' required");?></td>
        <td></td>
      </tr>
      <tr class='hidden'>
        <th><?php echo $lang->ticket->repeatTicket?></th>
        <td><?php echo html::select('repeatTicket', $tickets, 0, "class='form-control'");?></td>
      </tr>
      <tr class='hidden'>
        <th><?php echo $lang->ticket->resolvedBy;?></th>
        <td><?php echo html::select('resolvedBy', $users, $app->user->account, "class='form-control chosen'");?></td>
      </tr>
      <tr class='hidden'>
        <th><?php echo $lang->ticket->resolvedDate;?></th>
        <td><div class='datepicker-wrapper datepicker-date'><?php echo html::input('resolvedDate', helper::now(), "class='form-control form-date'");?></div></td>
      </tr>
      <tr class='hidden'>
        <th><?php echo $lang->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildoldform', 'fileCount=1&percent=0.85');?></td>
      </tr>
      <tr class='hidden'>
        <th><?php echo $lang->ticket->resolution;?></th>
        <td colspan='2'><?php echo html::textarea('resolution', '', "rows='5' class='w-p100'");?></td>
      </tr>
      <?php $this->printExtendFields($ticket, 'table');?>
      <tr>
        <th><?php echo $lang->comment;?></th>
        <td colspan='2'><?php echo html::textarea('comment', '', "rows='5' class='w-p100'");?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
  <hr class='small' />
  <div class='main'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
</div>
<script>
$('#closedReason').change(function()
{
    $('form table td').removeClass('required')
    $('#closedReason').closest('td').addClass('required')
    var value  = $(this).val();
    $('#repeatTicket').parents('tr').toggleClass('hidden', (value !== 'repeat'));
    $('#resolvedBy').parents('tr').toggleClass('hidden', (value !== 'commented'));
    $('#resolvedDate').parents('tr').toggleClass('hidden', (value !== 'commented'));
    $('.file-input-list').parents('tr').toggleClass('hidden', (value !== 'commented'));
    $('#resolution').parents('tr').toggleClass('hidden', (value !== 'commented'));

    if(value == 'commented')
    {
        $('#resolvedBy').closest('td').addClass('required')
        $('#resolvedDate').closest('td').addClass('required')
        $('#resolution').closest('td').addClass('required')
    }

    if(value == 'repeat')
    {
        $('#repeatTicket').closest('td').addClass('required')
    }
});

$('#repeatTicket').picker({disableEmptySearch:true})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
