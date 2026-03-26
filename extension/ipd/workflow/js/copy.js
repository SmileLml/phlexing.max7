$(document).ready(function()
{
    $('#app').change();
    $('input[name=approval]').change(function()
    {
        var approval = $(this).val();
        $('.approval').toggle(approval == 'enabled');
    })
    $('input[name=approval][checked=checked]').change();
});
