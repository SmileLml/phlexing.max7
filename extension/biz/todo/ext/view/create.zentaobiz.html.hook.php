<?php js::set('feedbackID', !empty($feedback) ? $feedback->id : '');?>
<script language='Javascript'>
$(document).ready(function()
{
    $("#navbar .nav li[data-id=browse]").addClass('active');

    if(feedbackID)
    {
        $('#type').attr('onchange', "loadList($('#type').val(), 0, $('#type').val(), feedbackID);")
        $('#type').val('feedback').change();
    }
})
</script>
