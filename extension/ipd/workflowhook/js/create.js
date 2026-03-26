$(function()
{
    $.setAjaxForm('#createHookForm', function(response)
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

    $('.hookForm #conditionType').change();
    $('.hookForm #action').change();
    $('.hookForm [name*=paramType]').change();
})
