$(function()
{
    /* Get checked opportunities. */
    $('#importToLib').on('click', function()
    {
        var opportunityIDList = '';
        $("input[name^='opportunityIDList']:checked").each(function()
        {
            opportunityIDList += $(this).val() + ',';
            $('#opportunityIDList').val(opportunityIDList);
        });
    });
})
