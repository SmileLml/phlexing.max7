$(document).ready(function()
{
    $('.prevTR select').change(function()
    {
        loadPrevData($(this).parents('tr'), $(this).val());
    });

    $('.prevTR').each(function()
    {
        loadPrevData($(this));
    });
})
