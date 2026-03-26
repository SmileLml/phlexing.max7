<?php
/**
 * The import confluence view file of convert module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     convert
 * @link        https://www.zentao.net
 */
namespace zin;

include('confluenceside.html.php');

$importUrl = inlink('importConfluence', "mode=import&type=user&nextObject=no&createTable=true");

jsVar('langImporting', $lang->convert->jira->importingAB);
jsVar('langImportFailed', $lang->convert->importFailed);

div
(
    setClass('flex'),
    panel
    (
        setClass('w-1/4 mr-4'),
        $sideBar
    ),
    panel
    (
        setClass('flex-1 p-4 scrollbar-thin scrollbar-hover'),
        div(setClass('panel-title text-lg'), $lang->convert->confluence->importData),
        h::ul
        (
            setID('importResult'),
            setClass('mx-4 my-2'),
            setStyle(array('list-style' => 'disc')),
            li
            (
                setClass('text-danger font-bold importing my-1 hidden'),
                $lang->convert->jira->importing
            )
        ),
        button
        (
            on::click("importConfluence(event, '{$importUrl}', true)"),
            setClass('btn primary'),
            $lang->convert->jira->start
        )
    )
);

render();
