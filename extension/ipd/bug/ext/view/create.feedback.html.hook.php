<?php if(isset($feedbackID)):?>
<?php
js::set('notifyEmail', $feedback->notifyEmail);
js::set('feedbackBy', $feedback->feedbackBy);
?>
<?php $inputHtml = html::hidden('feedback', (int)$feedbackID) . html::hidden('found', $feedback->openedBy);?>
<script language='Javascript'>
$(function()
{
    $(this).find('#dataform').children('table').find('tr:last').children('td:last').append(<?php echo json_encode($inputHtml)?>);
    $('#notifyEmail').val(notifyEmail).attr('readonly', true);
    $('#feedbackBy').val(feedbackBy).attr('readonly', true);
})
</script>
<?php endif;?>

<?php if(isset($ticketID)):?>
<?php $inputHtml = html::hidden('ticket', $ticketID);?>
<script language='Javascript'>
$(function()
{
    $(this).find('#dataform').children('table').find('tr:last').children('td:last').append(<?php echo json_encode($inputHtml)?>);
})
</script>
<?php endif;?>
