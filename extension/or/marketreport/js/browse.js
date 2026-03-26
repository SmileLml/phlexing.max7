$(function()
{
    $('input[name^="involvedReport"]').click(function()
    {
        var involved = $(this).is(':checked') ? 1 : 0;
        $.cookie('involvedReport', involved, {expires: config.cookieLife, path: config.webRoot});
        location.href = location.href;
    });
})
