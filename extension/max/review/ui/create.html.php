<?php
/**
 * The create view file of review module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     review
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('objectList', $lang->baseline->objectList);
jsVar('projectID',  $projectID);
jsVar('reviewText', $lang->review->common);
formPanel
(
    set::title($lang->review->create),
    $this->session->hasProduct ? formGroup
    (
        set::label($lang->review->product),
        set::width('1/2'),
        set::name('product'),
        set::items($products),
        set::value($productID),
        on::change('productChange')
    ) : formHidden('product', $productID),
    formGroup
    (
        set::label($lang->review->object),
        set::width('1/2'),
        picker
        (
            set::name('object'),
            set::items($lang->baseline->objectList),
            set::value($object),
            bind::change('objectChange')
        )
    ),
    formGroup
    (
        set::label($lang->review->content),
        set::width('1/2'),
        radioList
        (
            set::name('content'),
            set::items($lang->review->contentList),
            set::value('template'),
            set::inline(true),
            on::change('contentChange')
        )
    ),
    formGroup
    (
        set::hidden(true),
        set::label($lang->review->doclib),
        set::width('1/2'),
        set::name('doclib'),
        set::items($libs),
        on::change('doclibChange')
    ),
    formGroup
    (
        set::hidden(true),
        set::label($lang->review->doc),
        set::width('1/2'),
        set::name('doc'),
        set::items(array())
    ),
    formGroup
    (
        set::label($lang->review->title),
        set::width('1/2'),
        set::name('title')
    ),
    $config->edition == 'ipd' ? formGroup
    (
        set::label($lang->review->begin),
        set::width('1/2'),
        set::control('datePicker'),
        set::name('begin')
    ) : null,
    formGroup
    (
        set::label($lang->review->deadline),
        set::width('1/2'),
        set::control('datePicker'),
        set::name('deadline')
    ),
    formGroup
    (
        set::label($lang->review->files),
        fileSelector()
    ),
    formGroup
    (
        set::label($lang->review->reviewer),
        div(setID('reviewerBox'), setClass('h-8 content-center'), span($lang->noData))
    ),
    formGroup
    (
        set::label($lang->review->comment),
        set::name('comment'),
        set::control('editor')
    )
);
