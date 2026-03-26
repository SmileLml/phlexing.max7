<?php
/**
 * The cancel view file of opportunity module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     opportunity
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->opportunity->cancel));
formPanel
(
    formGroup
    (
        set::label($lang->opportunity->cancelReason),
        set::width('1/2'),
        set::name('cancelReason'),
        set::items($lang->opportunity->cancelReasonList)
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::rows(6)
    )
);
