<?php
/**
 * The edit view file of review module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     review
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->review->edit),
    $this->session->hasProduct ? formGroup
    (
        set::label($lang->review->product),
        set::width('1/2'),
        set::name('product'),
        set::items($products),
        set::value($review->product),
        set::required(true)
    ) : formHidden('product', $review->product),
    formGroup
    (
        set::label($lang->review->object),
        set::width('1/2'),
        picker
        (
            set::name('object'),
            set::items($lang->baseline->objectList + array($review->category => $review->category)),
            set::value($review->category),
            set::required(true),
            set::disabled(true)
        )
    ),
    formGroup
    (
        set::label($lang->review->title),
        set::width('1/2'),
        set::name('title'),
        set::value($review->title)
    ),
    formGroup
    (
        set::label($lang->review->deadline),
        set::width('1/2'),
        set::control('datePicker'),
        set::name('deadline'),
        set::value(helper::isZeroDate($review->deadline) ? '' : $review->deadline)
    ),
    formGroup
    (
        set::label($lang->review->comment),
        set::name('comment'),
        set::control('editor')
    ),
    formGroup
    (
        set::label($lang->review->files),
        fileSelector(set::defaultFiles(array_values($fileList)))
    )
);
