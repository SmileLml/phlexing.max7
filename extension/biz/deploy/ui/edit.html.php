<?php
/**
 * The edit view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;

$linkProducts = array();
if(!empty($deploy->products))
{
    foreach($deploy->products as $key => $product)
    {
        $linkProducts[] =
            formGroup
            (
                set::label($key > 0 ? '' : $lang->deploy->lblProduct),
                set::className('product-box w-full'),
                inputGroup
                (
                    $lang->deploy->product,
                    picker(set::name("products[$key]"), set::items($products), set::value($product->product), set::className('linkProduct')),
                    span
                    (
                        $lang->deploy->release,
                        set::className('input-group-addon')
                    ),
                    picker(set::name("release[$key]"), set::className('releases'), set::items(zget($releaseGroup, $product->product, array())), set::value($product->release)),
                    div
                    (
                        $key > 0 ? set::className('c-action flex') : set::className('c-action first-action flex'),
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
            );
    }
}

formPanel
(
    on::click('.add-item', 'window.addItem'),
    on::click('.delete-item', 'window.removeItem'),
    on::change('[name^="products"]', 'window.loadRelease'),
    set::title($lang->deploy->editAction),
    set::labelWidth(common::checkNotCN() ? '150px' : '100px'),
    setID('editPanel'),
    set::submitBtnText($lang->save),
    formGroup
    (
        setID('name'),
        set::width('1/2'),
        set::className('items-center'),
        set::label($lang->deploy->name),
        set::name('name'),
        set::value($deploy->name),
        set::required(true)
    ),
    formGroup(setID('host'), set::width('1/2'), set::label($lang->deploy->hosts), set::name('host'), set::control('picker'), set::items(zget($deploy, 'type', '') == 'spug' ? array() : $hosts), set::value(zget($deploy, 'type', '') == 'spug' ? '' : $deploy->host)),
    formGroup
    (
        setID('owner'),
        set::width('1/2'),
        set::label($lang->deploy->owner),
        set::required(true),
        picker
        (
            set::name('owner'),
            set::items($users),
            set::value($deploy->owner)
        )
    ),
    formGroup
    (
        set::label($lang->deploy->members),
        set::width('1/2'),
        picker
        (
            set::name('members[]'),
            set::items($users),
            set::value($deploy->members),
            set::multiple(true)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->deploy->estimate),
            set::required(true),
            set::control('datetimepicker'),
            set::name('estimate'),
            set::value($deploy->estimate)
        )
    ),
    formRow
    (
        setID('productRow'),
        set::className('flex-wrap'),
        $linkProducts ? $linkProducts :
        formGroup
        (
            set::label($lang->deploy->lblProduct),
            set::className('product-box w-full'),
            inputGroup
            (
                $lang->deploy->product,
                picker(set::name('products[0]'), set::items($products), set::className('linkProduct')),
                span
                (
                    $lang->deploy->release,
                    set::className('input-group-addon')
                ),
                picker(set::name('release[0]'), set::className('releases'), set::items(array())),
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
    formGroup(setID('desc'), set::className('items-center'), set::name('desc'), set::label($lang->deploy->desc), set::control('editor'), set::value($deploy->desc), set::rows(10))
);
