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

$(document).ready(function()
{
    $('#tabReport').addClass('block opacity-0');
    $('#mainContent .nav.nav-tabs').trigger('show', [{}, '#tabReport']);

    setTimeout(function()
    {
        $('#tabReport div[id^="chart"]').each(function()
        {
            if(echarts.getInstanceByDom(this).getWidth() == '100')
            {
                echarts.getInstanceByDom($(this)[0]).resize({width: 580});
                echarts.getInstanceByDom($(this)[0]).on('finished', function(){
                    chartImgData = $(this._dom).find('canvas').get(0).toDataURL("image/png");
                    chartID = $(this._dom).attr('id');
                    $('#fileName').after("<input type='hidden' name='" + chartID +"' id='" + chartID + "' />");
                    $('.modal-dialog #' + chartID).val(chartImgData);
                });
            }
            else
            {
                chartImgData = $(this).find('canvas').get(0).toDataURL("image/png");
                chartID = $(this).attr('id');
                $('#fileName').after("<input type='hidden' name='" + chartID +"' id='" + chartID + "' />");
                $('.modal-dialog #' + chartID).val(chartImgData);
            }
        });
        $('#tabReport').removeClass('block opacity-0');
    }, 200);
});
JAVASCRIPT
);

render();
