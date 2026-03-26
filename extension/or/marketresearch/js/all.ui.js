window.changeInvolved = function(event)
{
    var involved = $(event.target).is(':checked') ? 1 : 0;
    $.cookie.set('involvedResearch', involved, {expires: config.cookieLife, path: config.webRoot});
    window.reloadPage();
};
