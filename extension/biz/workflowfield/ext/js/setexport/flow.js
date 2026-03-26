$(function()
{
    $('.fieldBox .checkbox.check-all').each(function()
    {
        var $input = $(this).find('input[type=checkbox]');
        $(this).removeClass('checkbox').addClass('checkbox-primary');
        $(this).find('label').attr('for', $input.attr('id'));
        $(this).find('label').before($input);
    });
})
