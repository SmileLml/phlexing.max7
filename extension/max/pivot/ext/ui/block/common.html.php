<?php
/**
 * The columnitem file of pivot design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$fnGenerateHelpIcon = function($text, $useToggle = true, $placement = 'right')
{
    if(empty($text)) return span(setClass('text-warning'), icon('help'));

    return btn
    (
        setClass('inline ghost'),
        set::size('sm'),
        setData(array('title' => $text, 'placement' => $placement, 'className' => 'text-gray border border-light', 'type' => 'white', 'hideOthers' => true)),
        $useToggle ? set('data-toggle', 'tooltip') : null,
        icon('help')
    );
};

$fnGenerateExportBtn = function()
{
    return toolbar
    (
        hasPriv('pivot', 'export') ? item(set(array
        (
            'text'  => $this->lang->export,
            'icon'  => 'export',
            'class' => 'ghost',
            'data-target' => '#export',
            'data-toggle' => 'modal',
            'data-size'   => 'sm'
        ))) : null
    );
};
