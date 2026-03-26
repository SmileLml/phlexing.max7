<?php
/**
 * The detail view file of workflowgroup module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     workflowgroup
 * @link        https://www.zentao.net
 */
namespace zin;

panel
(
    setClass('panel-form'),
    set::shadow(false),
    set::title($title),
    set::titleClass('text-lg'),
    tableData
    (
        item(set::name($lang->workflowgroup->name), $group->name, $group->main == '1' ? label(setClass('gray-pale rounded-xl ml-2'), $lang->workflow->buildin) : null),
        $group->type == 'project' ? item(set::name($lang->workflowgroup->projectModel), zget($lang->workflowgroup->projectModelList, $group->projectModel)) : null,
        $group->type == 'project' ? item(set::name($lang->workflowgroup->projectType),  zget($lang->workflowgroup->projectTypeList,  $group->projectType))  : null,
        item(set::name($lang->workflowgroup->desc), $group->desc)
    )
);

hr();

history
(
    set::objectID($group->id)
);

render();
