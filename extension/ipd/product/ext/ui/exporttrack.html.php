<?php
/**
* The UI file of file module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     file
* @link        https://www.zentao.net
*/

namespace zin;

$app->loadLang('file');

$isNotZh = commonModel::checkNotCN();
formPanel
(
    setID('exportPanel'),
    css('.modal-content{padding-top: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; padding-bottom: 1.25rem;}'),
    $isNotZh ? css('#exportPanel .form-label{width: 70px}') : null,
    $isNotZh ? css('#exportPanel .form-group{padding-left: 70px}') : null,
    $isNotZh ? css('#exportPanel .customFieldsBox .form-label{width: 100px}') : null,
    $isNotZh ? css('#exportPanel .customFieldsBox .form-group{padding-left: 100px}') : null,
    setCssVar('--form-horz-label-width', '4rem'),
    set::actions(array('submit')),
    set::submitBtnText($lang->export),
    on::submit('setDownloading'),
    formGroup
    (
        set::width('full'),
        set::label($lang->file->fileName),
        set::name('fileName'),
        set::value(!empty($fileName) ? $fileName : $lang->file->untitled),
        on::change('onChangeFileName'),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->file->extension),
        set::name('fileType'),
        set::items($exportFileTypeList),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->file->encoding),
        set::control('picker'),
        set::name('encode'),
        set::items(array('utf-8' => 'UTF-8')),
        set::value('utf-8'),
        set::disabled(true),
        set::required(true)
    )
);

set::title($lang->file->exportData);

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

window.onChangeFileName = function(event)
{
    var objFileName = $(event.target);
    if(objFileName.val() == '') objFileName.val('{$lang->file->untitled}');
}
JAVASCRIPT
);

render();
