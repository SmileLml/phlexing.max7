$().ready(function()
{
    $('input[type=radio]').change(function()
    {
        if($(this).val() == 'yes' && $(this).prop('checked'))
        {
            $(this).parents('td').next().find('textarea').hide().prop('disabled', true);
        }
        else
        {
            $(this).parents('td').next().find('textarea').show().prop('disabled', false);
        }
    });

    $('input[type=radio]').change();
});

function changeExecution(param)
{
    var executionID = $("#execution option:selected").val();
    var link = createLink('pssp', 'update', param + executionID);
    $(location).attr('href', link);
}

$('[data-toggle="popover"]').popover();
