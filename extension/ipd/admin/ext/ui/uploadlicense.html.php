<?php
/**
 * The uploadlicense view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->admin->uploadLicense),
    !empty($fixFile) ? set::actions(false) : null,
    empty($fixFile) ? input(set::type('file'), set::name('file')) : div
    (
        span(setClass('label secondary-pale w-full p-4 justify-start'), html(sprintf($lang->admin->notWritable, join('</code><code>', $fixFile)))),
        btn
        (
            setClass('mt-4'),
            setData(array('load' => 'modal')),
            on::click('loadModal()'),
            $lang->refresh
        )
    )
);
