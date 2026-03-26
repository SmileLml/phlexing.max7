<?php if(isset($feedbackID)):?>
<?php $inputHtml = html::hidden('feedback', (int)$feedbackID);?>
<script language='Javascript'>
$(function()
{
    $(this).find('#dataform').children('table').find('tr:last').children('td:last').append(<?php echo json_encode($inputHtml)?>);
    $('#after-tr').remove();
})
</script>
<?php endif;?>
