$(function()
{
    $('#mode').change(function()
    {
        $('.tips').hide();
        $('.tips.' + $(this).val() + 'Mode').show();
    });

    $('#mode').change();
})
