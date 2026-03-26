$(function()
{
    $('#account').change(function()
    {
        $('#role').val(roles[$(this).val()]);
    })
})
