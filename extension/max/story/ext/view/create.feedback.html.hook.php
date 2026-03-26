<?php js::set('objectType', isset($objectType) ? $objectType : '');?>
<?php if(isset($feedbackID)):?>
<?php $feedback  = $this->loadModel('feedback')->getByID($feedbackID);?>
<?php $inputHtml = html::hidden('feedback', (int)$feedbackID);?>
<?php js::set('storyType', $type);?>
<?php
$replaceHtml  = "<td colspan='2' id='feedbackBox'>";
$replaceHtml .= "<div class='input-group' id='feedbackBox'>";
$replaceHtml .= '<div class="input-group">';
$replaceHtml .= '<div class="input-group-addon" style="min-width: 77px;">' . $lang->story->feedbackBy . '</div>';
$replaceHtml .= html::input('feedbackBy', $feedback->feedbackBy, "class='form-control' readonly");
$replaceHtml .= "<span class='input-group-addon'>" . $lang->story->notifyEmail . '</span>';
$replaceHtml .= html::input('notifyEmail', $feedback->notifyEmail, "class='form-control' readonly");
$replaceHtml .= '</div>';
$replaceHtml .= '</div>';
$replaceHtml .= '</td>';
?>
<script language='Javascript'>
$(function()
{
    $(this).find('#dataform').children('table').find('tr:last').children('td:last').append(<?php echo json_encode($inputHtml)?>);
    $("#navbar .nav li[data-id='browse']").addClass('active');
    $('#feedbackBox').replaceWith(<?php echo json_encode($replaceHtml);?>);

    if(storyType == 'story')
    {
        $('#source').on('change', function()
        {
            $('#source').closest('td').attr('colspan', 1);
            $('#sourceNote').closest('td').attr('colspan', 1);
        });
        $('#source').change();
    }
})
</script>
<?php endif;?>

<?php if(isset($ticketID)):?>
<?php $inputHtml = html::hidden('ticket', $ticketID);?>
<script language='Javascript'>
$(function()
{
    $("#navbar .nav li[data-id=" + objectType + "]").addClass('active');
    $(this).find('#dataform').children('table').find('tr:last').children('td:last').append(<?php echo json_encode($inputHtml)?>);
})

</script>
<?php endif;?>
