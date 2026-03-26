$(function()
{
    $('#navbar ul.nav li[data-id="unclosed"]').addClass('active');

    $.get(createLink('feedback', 'ajaxGetStatus', 'methodName=create'), function(status)
    {
        $('#status').val(status).change();
    });
});
