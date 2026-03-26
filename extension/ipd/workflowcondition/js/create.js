$(function()
{
    $('#tips a').click(function()
    {
        $.getJSON(createLink('workflowcondition', 'know'), function(response)
        {
            if(response.result == 'success') $('#tips').remove();
            if(response.result == 'fail') bootbox.alert(response.message);
        });

        return false;
    });

    $.setAjaxForm('#createConditionForm', function(response)
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

    $('#conditionTable #conditionType').change();
})
