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

$canApproval = !empty($config->openedApproval) && $this->config->edition != 'biz';

jsVar('positionList', $lang->workflow->positionList);
if($flow->positionModule == 'my') unset($lang->workflow->positionList['before']);

formPanel
(
    set::title($title),
    set::labelWidth(common::checkNotCN() ? '125px' : '70px'),
    set::submitBtnText($lang->save),
    set::ajax(['submitDisabledValue' => false]),
    on::change('[name=navigator]', 'toggleNavigator'),
    on::change('[name=app]', 'toggleApp'),
    on::change('[name=approval]', 'toggleApproval'),
    formGroup(set::label($lang->workflow->source), set::name('source'), set::value($flow->name), set::disabled()),
    formGroup
    (
        set::label($lang->workflow->name),
        set::required(),
        inputGroup
        (
            input(set::name('name')),
            iconPicker
            (
                set::name('icon'),
                set::items($config->workflow->icons),
                set::value($flow->icon)
            )
        )
    ),
    formGroup
    (
        set::label($lang->workflow->module),
        set::name('module'),
        set::placeholder($lang->workflow->placeholder->module),
        set::required()
    ),
    formGroup
    (
        set::label($lang->workflow->belong),
        inputGroup
        (
            picker
            (
                set::name('belong'),
                set::items($lang->workflow->belongList),
                set::value($flow->belong)
            ),
            btn
            (
                set::icon('help'),
                toggle::tooltip(array('placement' => 'right', 'title' => $lang->workflow->tips->belong, 'type' => 'white', 'class-name' => 'text-gray border border-light')),
                set::square(true),
                setClass('ghost h-6 mt-0.5 tooltip-btn')
            )
        )
    ),
    formGroup
    (
        set::label($lang->workflow->navigator),
        set::name('navigator'),
        set::items($lang->workflow->navigators),
        set::value($flow->navigator)
    ),
    formGroup
    (
        set::label($lang->workflow->app),
        set::hidden($flow->navigator != 'secondary'),
        picker
        (
            set::name('app'),
            set::items($apps),
            set::value($flow->app)
        )
    ),
    formGroup
    (
        set::label($lang->workflow->position),
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
                set::value($flow->position)
            )
        )
    ),
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
    $canApproval ? formGroup
    (
        set::label($lang->workflowapproval->approval),
        div
        (
            radiolist
            (
                set::name('approval'),
                set::items($lang->workflowapproval->approvalList),
                set::value($flow->approval),
                set::inline()
            ),
            span(setClass('text-warning'), $lang->workflowapproval->openLater)
        )
    ) : null,
    $canApproval ? formGroup
    (
        set::label($lang->workflowapproval->approvalFlow),
        set::name('approvalFlow'),
        set::items($approvalFlows),
        set::value($approvalFlow),
        set::hidden($flow->approval == 'disabled')
    ) : null,
    formHidden('type', $flow->type)
);
