<?php
/**
 * The create view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;
global $app;

formPanel
(
    on::click('.add-item', 'window.addItem'),
    on::click('.delete-item', 'window.removeItem'),
    on::change('[name^=products]', 'window.loadRelease'),
    setID('createPanel'),
    set::title($lang->deploy->create),
    set::labelWidth(common::checkNotCN() ? '150px' : '100px'),
    formGroup
    (
        setID('name'),
        set::width('1/2'),
        set::className('items-center'),
        set::label($lang->deploy->name),
        set::name('name'),
        set::required(true)
    ),
    formGroup
    (
        setID('host'),
        set::width('1/2'),
        set::label($lang->deploy->hosts),
        set::name('host'),
        set::control('picker'),
        set::items($hosts)
    ),
    formGroup
    (
        setID('owner'),
        set::width('1/2'),
        set::label($lang->deploy->owner),
        set::required(true),
        set::name('owner'),
        set::control('picker'),
        set::items($users),
        set::value($app->user->account)
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->deploy->members),
        set::name('members[]'),
        set::control('picker'),
        set::items($users),
        set::multiple(true)
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->deploy->estimate),
        set::required(true),
        set::control('datetimepicker'),
        set::name('estimate')
    ),
    formRow
    (
        setID('productRow'),
        set::className('flex-wrap'),
        formGroup
        (
            set::label($lang->deploy->lblProduct),
            set::className('product-box w-full'),
            inputGroup
            (
                $lang->deploy->product,
                picker(set::name('products[0]'), set::items($products), set::value($product), set::className('linkProduct')),
                span
                (
                    $lang->deploy->release,
                    set::className('input-group-addon')
                ),
                picker
                (
                    set::name('release[0]'),
                    set::className('releases'),
                    set::items($releases)
                ),
                div
                (
                    set::className('c-action first-action flex'),
                    span
                    (
                        setClass('input-group-addon'),
                        h::a
                        (
                            setClass('add-item'),
                            set::href('javascript:void(0)'),
                            icon('plus')
                        )
                    ),
                    span
                    (
                        setClass('input-group-addon delete-span'),
                        a
                        (
                            setClass('delete-item'),
                            set::href('javascript:void(0)'),
                            icon('close')
                        )
                    )
                )
            )
        )
    ),
    formGroup(setID('desc'), set::className('items-center'), set::name('desc'), set::label($lang->deploy->desc), set::control('editor'), set::rows(10))
);

render();
