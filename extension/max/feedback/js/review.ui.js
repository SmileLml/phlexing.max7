window.resultChange = function()
{
    const result = $('[name=result]').val();
    if(result == 'pass')
    {
        $('.assignedToBox').removeClass('hidden');
        $('.assignedToBox [name=assignedTo]').zui('picker').$.setValue(assignedTo);
    }
    else
    {
        $('.assignedToBox').addClass('hidden');
        $('.assignedToBox [name=assignedTo]').zui('picker').$.setValue(openedBy);
    }

    $.post($.createLink('feedback', 'ajaxGetStatus', 'methodName=review'), {'result' : result}, function(status)
    {
        $('[name=status]').val(status);
    });
}
