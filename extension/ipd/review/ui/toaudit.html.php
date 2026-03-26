<?php
/**
 * The toaudit view file of review module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     review
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->review->toAudit));

form
(
    formGroup
    (
        set::width('1/2'),
        set::label($lang->review->auditedBy),
        picker(set::name('auditedBy'), set::items($teamMembers)),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor')
    )
);

history();
