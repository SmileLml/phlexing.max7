$(document).ready(function()
{
    $('#verificationTable #type').change(function()
    {
        $('.sqlTR').toggle($(this).val() == 'sql');
        $('.dataTR').toggle($(this).val() == 'data');
        $('.messageTR').toggle($(this).val() != 'no');
    });

    $('#verificationTable #type').change();
    $('#verificationTable [name*=paramType]').change();
})
