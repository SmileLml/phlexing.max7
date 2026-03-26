$('.createContact').click(function()
{
    $('#triggerModal').load($(this).prop('href'), function()
    {
        $.zui.ajustModalPosition();
    });
    return false;
});
