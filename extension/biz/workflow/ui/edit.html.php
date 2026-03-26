<?php
/**
 * The edit view file of workflow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     workflow
 * @link        https://www.zentao.net
 */
namespace zin;

$isRequired     = $flow->status == 'normal';
$notBuiltinFlow = $flow->type   == 'flow' && !$flow->buildin;

$subTableSyncTips = '';
if($quoteTables)
{
    $groupName = array_column($quoteTables, 'groupName');
    $subTableSyncTips = sprintf($lang->workflow->tips->subTableSync, ' ' . implode(', ', $groupName). ' ');
}

jsVar('positionList', $lang->workflow->positionList);
if($flow->positionModule == 'my') unset($lang->workflow->positionList['before']);

formPanel
(
    set::title($title),
    set::labelWidth(common::checkNotCN() ? '125px' : '70px'),
    set::submitBtnText($lang->save),
    on::change('[name=navigator]', 'toggleNavigator'),
    on::change('[name=app]', 'toggleApp'),
    formGroup
    (
        set::label($lang->workflow->name),
        set::required(),
        inputGroup
        (
            input
            (
                set::name('name'),
                set::value($flow->name),
                set::readonly($flow->buildin || $flow->role == 'quote')
            ),
            ($flow->type == 'flow' && !$flow->buildin) ? iconPicker
            (
                set::name('icon'),
                set::items($config->workflow->icons),
                set::value($flow->icon)
            ) : null
        )
    ),
    formGroup
    (
        set::label($lang->workflow->module),
        set::name('module'),
        set::value($flow->module),
        set::readonly(),
        set::required()
    ),
    $notBuiltinFlow && $config->vision != 'lite' ? formGroup(set::label($lang->workflow->belong), inputGroup
    (
        picker
        (
            set::disabled(!empty($groupID)),
            set::name('belong'),
            set::items($lang->workflow->belongList),
            set::value($flow->belong)
        ),
        div
        (
            setClass('input-group-addon'),
            btn
            (
                set::icon('help'),
                toggle::tooltip(array('placement' => 'right', 'title' => $lang->workflow->tips->belong, 'type' => 'white', 'class-name' => 'text-gray border border-light')),
                set::square(true),
                setClass('ghost h-6 mt-0.5 tooltip-btn')
            )
        )
    )) : null,
    $notBuiltinFlow && !empty($groupID) ? div(setClass('flex'), div(setStyle('width', '70px')), span(setClass('text-warning'), $lang->workflow->tips->belongDisabled)) : null,
    $notBuiltinFlow ? formGroup
    (
        set::label($lang->workflow->navigator),
        set::required($isRequired),
        picker
        (
            set::name('navigator'),
            set::items($lang->workflow->navigators),
            set::value($flow->navigator)
        )
    ) : null,
    $notBuiltinFlow ? formGroup
    (
        set::label($lang->workflow->app),
        set::required($isRequired),
        set::hidden($flow->navigator != 'secondary'),
        inputGroup
        (
            picker
            (
                set::name('app'),
                set::items($apps),
                set::value($flow->app)
            )
        )
    ) : null,
    $notBuiltinFlow ? formGroup
    (
        set::label($lang->workflow->position),
        set::required($isRequired),
        inputGroup
        (
            picker
            (
                on::change('[name=positionModule]', 'loadDropdownMenu'),
                set::name('positionModule'),
                set::items($menus),
                set::value($flow->positionModule)
            ),
            div(setID('dropMenus'), $dropMenus ? picker
            (
                setStyle(array('width' => '120px')),
                set::name('dropMenu'),
                set::items($dropMenus),
                set::value($flow->dropMenu)
            ) : null),
            picker
            (
                set::name('position'),
                set::items($lang->workflow->positionList),
                set::value($flow->position),
                set::required($isRequired)
            )
        )
    ) : null,
    formGroup
    (
        set::label($lang->workflow->desc),
        textarea
        (
            set::name('desc'),
            set::value($flow->desc),
            set::rows(3)
        )
    ),
    ($flow->role == 'custom' && $subTableSyncTips) ? div(setClass('alert warning-pale"'), div(setClass('alert-text'), $subTableSyncTips)) : null
);
