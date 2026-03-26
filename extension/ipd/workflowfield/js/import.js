$(function()
{
    $.setAjaxForm('#importForm', function(response)
    {
        if(response.result == 'success')
        {
            $('#triggerModal').load(response.locate, function()
            {
                $.zui.ajustModalPosition();
            });

            return false;
        }
    });
})
