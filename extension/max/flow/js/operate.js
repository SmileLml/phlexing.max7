$(document).ready(function()
{
    $.setAjaxForm('#operateForm');

    $('#dataID').change(function()
    {
        location.href = createLink(window.moduleName, window.action, 'dataID=' + $(this).val());
    });

    $('.prevTR select').change(function()
    {
        loadPrevData($(this).parents('tr'), $(this).val());
    });

    $('.prevTR').each(function()
    {
        loadPrevData($(this));
    });
})
