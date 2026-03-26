<?php
/**
 * The ajaxgetrelatedobjects view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@chandao.com>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;
div
(
    set::className('docRelatedObjects'),
    div
    (
        set::className('font-bold px-1'),
        $lang->doc->docLang->relateObject
    ),
    relatedObjectList
    (
        set::objectID($docID),
        set::objectType('doc'),
        set::relatedObjects($relatedObjects),
        set::browseType('byObject')
    )
);
