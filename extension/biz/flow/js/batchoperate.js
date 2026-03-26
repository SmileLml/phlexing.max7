$(function()
{
    $(document).on('change', 'input,select,textarea,radio,checkbox', function()
    {
        $(this).css('border-color', '');
        $(this).next('.text-error.red').remove();
    });
})
