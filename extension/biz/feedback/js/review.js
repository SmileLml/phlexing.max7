$(function()
{
    $('#result').change(function()
    {
        if($(this).val() == 'pass')
        {
            $('#assignedToBox').removeClass('hide');
            $('#assignedToBox #assignedTo').val(assignedTo).trigger('chosen:updated');
        }
        else
        {
            $('#assignedToBox').addClass('hide');
            $('#assignedToBox #assignedTo').val(openedBy).trigger('chosen:updated');
        }

        $.post(createLink('feedback', 'ajaxGetStatus', 'methodName=review'), {'result' : $(this).val()}, function(status)
        {
            $('#status').val(status).change();
        });
    });
    $('#result').change();
})
