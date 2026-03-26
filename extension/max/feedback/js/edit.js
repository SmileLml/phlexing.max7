$(function()
{
    $.get(createLink('feedback', 'ajaxGetStatus', 'methodName=update&status=' + $('#status').val()), function(status)
    {
        $('#status').val(status).change();
    });
});
