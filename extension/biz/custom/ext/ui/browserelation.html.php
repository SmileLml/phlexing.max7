<?php
/**
 * The browseRelation view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie<xieqiyu@chandao.com>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

$tableData = initTableData($relationList, $config->custom->browseRelation->dtable->fieldList);

featureBar
(
    div
    (
        setClass('text-md font-bold'),
        $lang->custom->relation,
        span
        (
            setClass('text-sm text-warning font-medium px-1'),
            icon('help'),
            $lang->custom->relationTip
        )
    )
);

toolbar
(
    hasPriv('custom', 'createRelation') ? btn
    (
        set::icon('plus'),
        set::className('primary'),
        set::url(createLink('custom', 'createRelation')),
        setData('toggle', 'modal'),
        $lang->custom->createRelation
    ) : null
);

dtable
(
    set::bordered(true),
    set::cols($config->custom->browseRelation->dtable->fieldList),
    set::data($tableData),
    set::onRenderCell(jsRaw('window.renderCell'))
);
