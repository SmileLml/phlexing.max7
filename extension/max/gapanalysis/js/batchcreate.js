$(function()
{
    $("[id*='account']").change(function()
    {
        var $account = $(this);
        $("[id*='account']").each(function()
        {
            if($(this).val() == $account.val() && $(this).attr('id') != $account.attr('id'))
            {
                alert(errorSameAccount);
                $account.val('').trigger("chosen:updated");
                return false;
            }
        })

        $(this).parent().parent('tr').find("[id*='role']").val(roles[$(this).val()]);
    })
})
