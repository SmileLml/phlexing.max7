$(function()
{
    /* Get checked issues. */
    $('#importToLib').on('click', function()
    {
        var issueIDList = '';
        $("input[name^='issueIDList']:checked").each(function()
        {
            issueIDList += $(this).val() + ',';
            $('#issueIDList').val(issueIDList);
        });
    });
})
