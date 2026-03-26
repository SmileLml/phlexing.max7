<?php
/**
 * The edit view file of feedback module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     feedback
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('feedbackID', $feedback->id);

formPanel(setID('feedbackEditForm'), set::title($lang->feedback->edit), on::change('[name=product]', 'changeProduct'), formRow
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
            set::value(!empty($productID) ? $productID : $feedback->product)
        )
    )
), formRow
(
    setID('moduleBox'),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->feedback->module),
        picker
        (
            set::name('module'),
            set::items($modules),
            set::required(true),
            set::value($feedback->module)
        )
    )
), formRow
(
    formGroup
    (
        set::width('1/2'),
        set::name('type'),
        set::label($lang->feedback->type),
        set::items($lang->feedback->typeList),
        set::value($feedback->type)
    )
), formRow
(
    formGroup
    (
        set::label($lang->feedback->title),
        set::required(true),
        inputGroup
        (
            input(set::name('title'), set::value($feedback->title)),
            checkbox
            (
                set::name('public'),
                set::text($lang->feedback->public),
                set::value(1),
                set::rootClass('btn'),
                set::checked($feedback->public)
            ),
            $lang->feedback->pri,
            pripicker(set::width('120px'), set::name('pri'), set::items($lang->feedback->priList), set::value($feedback->pri))
        )
    )
), formRow
(
    formGroup
    (
        set::label($lang->feedback->desc),
        editor(set::name('desc'), html($feedback->desc))
    )
), formRow
(
    formGroup
    (
        set::width('1/2'),
        set::name('feedbackBy'),
        set::label($lang->feedback->feedbackBy),
        set::value($feedback->feedbackBy)
    )
), formRow
(
    formGroup
    (
        set::width('1/2'),
        set::name('source'),
        set::label($lang->feedback->source),
        set::value($feedback->source)
    )
), formRow
(
    formGroup
    (
        set::width('1/2'),
        set::name('notifyEmail'),
        set::label($lang->feedback->notifyEmail),
        set::value($feedback->notifyEmail)
    )
), formGroup
(
    set::width('1/2'),
    set::label($lang->feedback->mailto),
    mailto
    (
        set::name('mailto'),
        set::items($users),
        set::value($feedback->mailto),
        set::multiple(true)
    )
), formGroup
(
    set::width('1/2'),
    set::label($lang->feedback->keywords),
    set::name('keywords'),
    set::value($feedback->keywords)
), formRow
(
    formGroup
    (
        set::label($lang->feedback->files),
        fileSelector($feedback->files ? set::defaultFiles(array_values($feedback->files)) : null)
    )
), formRow
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
                set::checked($feedback->notify)
            )
        )
    )
));

render();
