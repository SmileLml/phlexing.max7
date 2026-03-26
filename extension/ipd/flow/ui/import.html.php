<?php
/**
 * The import view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
namespace zin;

$importModeTips = array();
$hidden = '';
foreach($lang->flow->importModeList as $key => $label)
{
    $importModeTips[] = span
        (
            setClass("label secondary mode-tip {$key}Mode {$hidden}"),
            $lang->flow->tips->importMode[$key]
        );
    $hidden = 'hidden';
}

$toggleModeTip = jsCallback()->do('$(".mode-tip").addClass("hidden");$("." + $("[name=mode]").val() + "Mode").removeClass("hidden");');

formPanel(set::title($title), formGroup
(
    set::label($lang->flow->importMode),
    set::name('mode'),
    set::control(array('control' => 'picker', 'required' => true)),
    set::items($lang->flow->importModeList),
    on::change('[name=mode]', $toggleModeTip),
    $importModeTips
), formGroup(set::label($lang->files), input(set::type('file'), set::name('file'))));
h::js('$.cookie.set("maxImport", 0, {expires:config.cookieLife, path:config.webRoot});');
