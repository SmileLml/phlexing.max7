<?php
/**
 * The map confluence to zentao view file of convert module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     convert
 * @link        https://www.zentao.net
 */
namespace zin;

include('confluenceside.html.php');

$rows[] = div
(
    setClass('panel-title'),
    span(setClass('text-lg'), $lang->convert->confluence->spaceMap),
    span
    (
        icon('help self-center text-warning mr-1 pl-2'),
        setClass('self-center font-medium text-gray'),
        $lang->convert->confluence->mapSpaceDesc
    )
);
$rows[] = formRow
(
    div(setClass("w-1/4 text-center text-md font-bold"), $lang->convert->confluence->spaceKey),
    div(setClass("w-1/4 text-center text-md font-bold"), $lang->convert->confluence->space),
    div(setClass("flex-1 text-center text-md font-bold"), $lang->convert->confluence->zentaoSpace)
);

foreach($spaceList as $space)
{
    $spaceType    = $this->convert->checkConfluenceSpacePriv($space);
    $currentSpace = $spaceType == 'mine' ? 'mine' : 'custom';
    $currentSpace = !empty($relation['zentaoSpace'][$space->id]) ? $relation['zentaoSpace'][$space->id] : $currentSpace;
    $currentLib   = $currentSpace == 'mine' || $currentSpace == 'custom' ? 'defaultSpace' : '';
    $currentLib   = !empty($relation['zentaoDocLib'][$space->id]) ? $relation['zentaoDocLib'][$space->id] : $currentLib;
    $zentaoDocLib = $currentLib == 'defaultSpace' ? array('defaultSpace' => $lang->convert->confluence->defaultSpace) : array();
    if($currentSpace == 'product') $zentaoDocLib = $products['items'];
    if($currentSpace == 'project') $zentaoDocLib = $projects['items'];
    if($space->status == 'archived') $space->name .= "({$lang->convert->confluence->archived})";
    $rows[] = formRow
    (
        div(setClass("w-1/4 text-center text-ellipsis"), set::title($space->key), $space->key),
        div
        (
            setClass("w-1/4 text-center text-ellipsis"),
            set::title($space->name),
            $space->name,
            input
            (
                set::type('hidden'),
                set::name("confluenceSpace[$space->id]"),
                set::value($space->name)
            )
        ),
        div
        (
            setClass("flex-1 mx-2"),
            div
            (
                setClass('flex'),
                picker
                (
                    setData(array('on' => 'change', 'call' => 'changeSpace', 'params' => 'event')),
                    set::required(true),
                    set::name("zentaoSpace[$space->id]"),
                    set::items($zentaoSpace),
                    set::value($currentSpace)
                ),
                picker
                (
                    setClass('ml-2'),
                    set::required(true),
                    set::disabled($currentSpace == 'mine' || $currentSpace == 'custom' ? true : ''),
                    set::id("zentaoDocLib$space->id"),
                    set::name("zentaoDocLib[$space->id]"),
                    set::items($zentaoDocLib),
                    set::value($currentLib)
                )
            )
        )
    );
}

div
(
    setClass('flex'),
    panel
    (
        setClass('w-1/4 mr-4 scrollbar-thin scrollbar-hover'),
        setStyle(array('max-height' => 'calc(100vh - 130px)')),
        $sideBar
    ),
    formPanel
    (
        setClass('flex-1 m-0 p-0 scrollbar-thin scrollbar-hover'),
        setStyle(array('max-height' => 'calc(100vh - 130px)')),
        set::actionsClass('hidden'),
        $rows
    )
);
render();
