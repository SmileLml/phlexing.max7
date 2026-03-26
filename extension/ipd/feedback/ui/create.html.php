<?php
/**
 * The create view file of feedback module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     feedback
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('productID', $productID);

if(!isInModal()) dropmenu(set::text($productID == 'all' ? $lang->product->allProduct : ''), set::tab('product'));
formPanel
(
    setID('feedbackCreateForm'),
    set::title($lang->feedback->create),
    on::change('[name=product]', 'changeProduct'),
    !empty($feedback) ? set::data($feedback) : null,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->feedback->product),
            set::required(true),
            picker
            (
                set::name('product'),
                set::items($products),
                set::value((!empty($productID) && $productID !== 'all') ? $productID : '')
            )
        )
    ),
    formRow
    (
        setID('moduleBox'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->feedback->module),
            modulePicker
            (
                set::name('module'),
                set::items($modules),
                set::value(isset($feedback) ? $feedback->module : $moduleID),
                set::required(true),
                common::hasPriv('tree', 'browse') && ($app->user->admin || strpos(",{$app->user->view->products},", "{$productID}"))? set::manageLink(createLink('tree', 'browse', "rootID=$productID&viewType=feedback")) : null
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('type'),
            set::label($lang->feedback->type),
            set::items($lang->feedback->typeList),
            set::value(isset($feedback) ? $feedback->type : '')
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->feedback->title),
            set::required(true),
            inputGroup
            (
                input(set::name('title'), set::value(isset($feedback) ? $feedback->title : '')),
                checkbox
                (
                    set::name('public'),
                    set::text($lang->feedback->public),
                    set::value(1),
                    set::rootClass('btn'),
                    set::checked(isset($feedback) ? $feedback->public : 1)
                ),
                $lang->feedback->pri,
                pripicker(set::width('120px'), set::name('pri'), set::items($lang->feedback->priList), set::value(isset($feedback) ? $feedback->pri : 3))
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->feedback->desc),
            editor(set::name('desc'), set::value(isset($feedback) ? $feedback->desc : ''), set::templateType('feedback'))
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('feedbackBy'),
            set::label($lang->feedback->feedbackBy),
            set::value(isset($feedback) ? $feedback->feedbackBy : '')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('source'),
            set::label($lang->feedback->source),
            set::value(isset($feedback) ? $feedback->source : '')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('notifyEmail'),
            set::label($lang->feedback->notifyEmail),
            set::value(isset($feedback) ? $feedback->notifyEmail : '')
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->feedback->mailto),
        mailto
        (
            set::name('mailto'),
            set::items($users),
            set::value(isset($feedback) ? $feedback->mailto : ''),
            set::multiple(true)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('keywords'),
        set::label($lang->feedback->keywords),
        set::value(isset($feedback) ? $feedback->keywords : '')
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->feedback->files),
            fileSelector(set::defaultFiles(!empty($feedback->files) ? array_values($feedback->files) : array())),
            input(setClass('hidden'), set::name('fileList'), set::value(!empty($feedback->files) ? $feedback->files : array()))
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->feedback->notify),
            inputGroup
            (
                setClass('h-8 items-center'),
                checkbox
                (
                    set::name('notify'),
                    set::text($lang->feedback->mailNotify),
                    set::value(1),
                    set::checked(isset($feedback) ? $feedback->notify : 1)
                )
            )
        )
    )
);

render();
