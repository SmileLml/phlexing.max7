<?php
namespace zin;
unset($lang->exportFileTypeList);
if(is_dir($this->app->getCoreLibRoot() . 'word')) $lang->exportFileTypeList['word'] = 'word';
$lang->exportFileTypeList['html'] = 'html';

set::title($lang->export);

formPanel
(
    on::submit('setDownloading'),
    css('#fileType{width: 80px;}'),
    set::target('_self'),
    formGroup
    (
        set::label($lang->setFileName),
        inputGroup
        (
            input(set::name('fileName')),
            picker
            (
                set::id('fileType'),
                set::name('fileType'),
                set::required(true),
                set::items($lang->exportFileTypeList)
            )
        )
    )
);

h::js
(
<<<JAVASCRIPT
window.setDownloading = function(event)
{
    /* Doesn't support Opera, omit it. */
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;

    $.cookie.set('downloading', 0, {expires:config.cookieLife, path:config.webRoot});

    var time = setInterval(function()
    {
        if($.cookie.get('downloading') == 1)
        {
            const modal = zui.Modal.query(event.target);
            if(modal) modal.hide();
            $.cookie.set('downloading', null, {expires:config.cookieLife, path:config.webRoot});
            clearInterval(time);
        }
    }, 300);

    return true;
}
JAVASCRIPT
);
