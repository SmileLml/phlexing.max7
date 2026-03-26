<?php
/**
 * The release view file of workflow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     workflow
 * @link        https://www.zentao.net
 */
namespace zin;

if(!empty($errors))
{
    $warning = [];
    foreach($errors as $error) $warning[] = p(html($error));

    formPanel
    (
        set::title($title),
        set::actions([['text' => $lang->goback, 'class' => 'btn', 'data-dismiss' => 'modal']]),
        div(setClass('text-warning'), $warning)
    );
}
else
{
    jsVar('positionList', $lang->workflow->positionList);
    if($flow->positionModule == 'my') unset($lang->workflow->positionList['before']);
    formPanel
    (
        set::title($title),
        set::labelWidth(common::checkNotCN() ? '125px' : '80px'),
        set::submitBtnText($lang->save),
        on::change('[name=navigator]', 'toggleNavigator'),
        on::change('[name=app]', 'toggleApp'),
        formGroup
        (
            set::label($lang->workflow->navigator),
            set::required(),
            picker
            (
                set::name('navigator'),
                set::items($lang->workflow->navigators),
                set::value($flow->navigator)
            )
        ),
        formGroup
        (
            set::label($lang->workflow->app),
            set::required(),
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
        ),
        formGroup
        (
            set::label($lang->workflow->position),
            set::required(),
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
                    set::required()
                )
            )
        ),
        $flow->status == 'wait' && $flow->belong ? formGroup
        (
            set::label($lang->workflow->syncRelease),
            radioList
            (
                set::name('syncRelease'),
                set::value('all'),
                set::items($lang->workflow->syncReleaseList)
            )
        ) : null,
        $flow->status != 'wait' && $flow->belong ? formGroup
        (
            set::label(''),
            span(setClass('text-warning'), $lang->workflow->tips->release)
        ) : null,
        formHidden('module', $flow->module)
    );
}
