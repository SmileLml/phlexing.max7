<?php
/**
 * The createrelation view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie<xieqiyu@chandao.com>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('allRelationName', $allRelationName);
jsVar('hasRelationTip', $lang->custom->hasRelationTip);

formBatchPanel
(
    set::formID('relationForm'),
    set::title($lang->custom->createRelation),
    set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
    set::minRows(1),
    set::maxRows(1),
    formBatchItem
    (
        set::width('1/2'),
        set::label($lang->custom->relation),
        set::name('relation'),
        set::required(true)
    ),
    formBatchItem
    (
        set::width('1/2'),
        set::label($lang->custom->relativeRelation),
        set::name('relativeRelation'),
        set::required(true)
    )
);
