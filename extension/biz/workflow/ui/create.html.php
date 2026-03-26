<?php
/**
 * The create view file of workflow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     workflow
 * @link        https://www.zentao.net
 */
namespace zin;

$isFlow      = $type == 'flow';
$canApproval = $isFlow && !empty($config->openedApproval) && $config->edition != 'biz';

jsVar('positionList', $lang->workflow->positionList);

formPanel
(
    set::title($title),
    set::labelWidth(common::checkNotCN() ? '125px' : '90px'),
    set::submitBtnText($lang->save),
    on::change('[name=navigator]', 'toggleNavigator'),
    on::change('[name=app]', 'toggleApp'),
    on::change('[name=approval]', 'toggleApproval'),
    formGroup
    (
        set::label($lang->workflow->name),
        set::required(),
        inputGroup(input(set::name('name')), $isFlow ? iconPicker
        (
            set::name('icon'),
            set::items($config->workflow->icons)
        ): null)
    ),
    formGroup
    (
        set::label($lang->workflow->module),
        set::name('module'),
        set::placeholder($lang->workflow->placeholder->module),
        set::required()
    ),
    $isFlow && $config->vision != 'lite' ? formGroup
    (
        set::label($lang->workflow->belong),
        inputGroup
        (
            picker
            (
                set::name('belong'),
                set::items($lang->workflow->belongList)
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
        )
    ) : null,
    $isFlow ? formGroup
    (
        set::label($lang->workflow->navigator),
        set::name('navigator'),
        set::items($lang->workflow->navigators)
    ) : null,
    $isFlow ? formGroup
    (
        set::label($lang->workflow->app),
        inputGroup
        (
            picker
            (
                set::name('app'),
                set::items($apps)
            )
        ),
        set::hidden()
    ) : null,
    $isFlow ? formGroup
    (
        set::label($lang->workflow->position),
        inputGroup
        (
            picker
            (
                on::change('[name=positionModule]', 'loadDropdownMenu'),
                set::name('positionModule'),
                set::items([])
            ),
            div(setID('dropMenus')),
            picker
            (
                set::name('position'),
                set::items($lang->workflow->positionList)
            )
        )
    ) : null,
    formGroup
    (
        set::label($lang->workflow->desc),
        textarea
        (
            set::name('desc'),
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
                set::value('disabled'),
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
        set::hidden()
    ) : null,
    formHidden('type', $type),
    formHidden('parent', $parent)
);
