<?php
/**
 * The details file of metriclib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin<zhouxin@easycorp.ltd>
 * @package     metriclib
 * @link        http://www.zentao.net
 */
namespace zin;

$buildItems = function($items): array
{
    $itemList = array();
    foreach($items as $item)
    {
        $itemList[] = item
        (
            set::name($item['name']),
            !empty($item['href']) ? a
            (
                set::href($item['href']),
                !empty($item['attr']) && is_array($item['attr']) ? set($item['attr']) : null,
                html($item['text'])
            ) : html($item['text']),
            set::collapse(!empty($item['text']))
        );
    }

    return $itemList;
};

detailHeader
(
    to::title
    (
        entityLabel
        (
            setClass('text-xl font-black'),
            set::level(1),
            set::text($lang->metriclib->libraryDetails)
        )
    )
);

panel
(
    setClass('clear-shadow'),
    set::bodyClass('relative'),
    div
    (
        tableData
        (
            $buildItems($libDetails)
        )
    )
);

render();
