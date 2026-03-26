<?php
/**
 * The create view file of ticket module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     ticket
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('productID', $productID);
jsVar('isAdmin', $app->user->admin);
jsVar('authedProducts', empty($app->user->view->products) ? array() : explode(',', $app->user->view->products));

if(!isInModal()) dropmenu(set::text($productID == 'all' ? $lang->product->allProduct : ''), set::tab('product'));
formPanel
(
    setID('ticketCreateForm'),
    set::title($lang->ticket->create),
    on::change('[name=product]', 'loadAll'),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->ticket->product),
            set::required(true),
            picker
            (
                set::name('product'),
                set::items($products),
                set::value($productID)
            )
        ),
        formGroup
        (
            set::label($lang->ticket->module),
            strpos(",{$config->ticket->create->requiredFields},", ',module,') !== false ? set::labelClass('required') : null,
            inputGroup
            (
                setID('moduleBox'),
                picker(set(array('name' => 'module', 'required' => true, 'items' => $modules, 'value' => $moduleID))),
                common::hasPriv('tree', 'browse') ? span(setID('manageModule'), setClass('btn'), setClass((count($modules) > 1 || (!$app->user->admin && strpos(",{$app->user->view->products},", "{$productID}") === false)) ? 'hidden' : ''), icon('treemap'), setData(array('toggle' => 'modal', 'size' => 'lg')), set('href', createLink('tree', 'browse', "rootID={$productID}&viewType=ticket")), set('title', $lang->tree->manage)) : null
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->ticket->type),
            set::name('type'),
            set::items($lang->ticket->typeList),
            set::value($defaultType)
        ),
        formGroup
        (
            set::label($lang->ticket->openedBuild),
            set::name('openedBuild'),
            set::control(array('control' => 'picker', 'multiple' => true)),
            set::items($builds)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->ticket->assignedTo),
            set::name('assignedTo'),
            set::items($users),
            set::value(!empty($product->ticket) ? $product->ticket : '')
        ),
        formGroup
        (
            set::label($lang->ticket->deadline),
            set::name('deadline'),
            set::control('date')
        )
    ),
    formGroup
    (
        set::label($lang->ticket->from),
        div
        (
            setClass('customerBox w-full flex'),
            inputGroup($lang->ticket->customer, input
            (
                setClass('customerInput'),
                set::name('customer[0]'),
                set::value($customer)
            ), $lang->ticket->contact, input
            (
                setClass('contactInput'),
                set::name('contact[0]'),
                set::value($contact)
            ), span(setClass('input-group-addon' . (strpos(",{$config->ticket->create->requiredFields},", ',notifyEmail,') !== false ? ' required' : '')), $lang->ticket->notifyEmail), input
            (
                setClass('notifyEmailInput'),
                set::name('notifyEmail[0]'),
                set::value($email)
            )),
            div
            (
                setClass('pl-2 flex self-center line-btn c-actions first-action'),
                btn
                (
                    bind::click('addNewLine(event)'),
                    setClass('btn btn-link text-gray addLine'),
                    icon('plus')
                ),
                btn
                (
                    bind::click('removeLine(event)'),
                    setClass('btn btn-link text-gray removeLine'),
                    setClass('hidden'),
                    icon('trash')
                )
            )
        )
    ),
    formGroup
    (
        set::label($lang->ticket->title),
        set::control('colorInput'),
        set::name('title'),
        set::value($ticketTitle),
        inputGroup
        (
            setClass('priBox'),
            $lang->ticket->pri,
            priPicker
            (
                set::width('120px'),
                set::name('pri'),
                set::items($lang->ticket->priList),
                set::value($pri)
            ),
            $lang->ticket->estimate,
            input(setClass('estimateBox'), set::name('estimate'))
        )
    ),
    formGroup
    (
        set::label($lang->ticket->desc),
        set::control(array('control' => 'editor', 'templateType' => 'ticket')),
        set::name('desc'),
        set::value($desc)
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->ticket->mailto),
            set::name('mailto'),
            set::control('mailto'),
            set::items($users)
        ),
        formGroup
        (
            set::label($lang->ticket->keywords),
            set::name('keywords')
        )
    ),
    formGroup
    (
        set::label($lang->files),
        fileSelector()
    ),
    $fromType == 'feedback' ? formHidden('feedback', (int)$fromID) : null
);
