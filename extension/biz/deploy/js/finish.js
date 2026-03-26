$(function()
{
    $('#result').change(function()
    {
        $('#serviceBox').toggle($(this).val() == 'success' && showService);
        $('#hostsBox').toggle($(this).val() == 'success');
    })
});
