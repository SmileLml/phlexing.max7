$(function()
{
    $.setAjaxForm('#editLinkageForm', function(response)
    {
        if(response.result == 'success')
        {
            setTimeout(function()
            {
                $('#triggerModal').load(response.locate, function()
                {
                    $.zui.ajustModalPosition();
                });
            }, 1200);
        }
    });

    $('#linkageTable [name^=source]').change();
})
