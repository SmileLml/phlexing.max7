$(function()
{
    $('input[name^="involvedResearch"]').click(function()
    {
        var involved = $(this).is(':checked') ? 1 : 0;
        $.cookie('involvedResearch', involved, {expires: config.cookieLife, path: config.webRoot});
        location.reload();
    });
})
