<?php
/**
 * The view view file of workflow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     workflow
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
        item(set::name($lang->workflow->name), $flow->name),
        item(set::name($lang->workflow->module), $flow->module),
        item(set::name($lang->workflow->table), $flow->table),
        $flow->type == 'flow' ? item(set::name($lang->workflow->navigator), zget($lang->workflow->navigators, $flow->navigator)) : null,
        $flow->type == 'flow' && $flow->navigator == 'secondary' ? item(set::name($lang->workflow->app), zget($apps, $flow->app)) : null,
        $flow->type == 'flow' ? item(set::name($lang->workflow->position), html(zget($menus, $flow->positionModule) . ($flow->dropMenu ? $lang->arrow . zget($dropMenus, $flow->dropMenu) : '') . zget($lang->workflow->positionList, $flow->position))) : null,
        $flow->type == 'flow' ? item(set::name($lang->workflowapproval->approval), zget($lang->workflowapproval->approvalList, $flow->approval)) : null,
        $flow->type == 'flow' && $flow->approval  == 'enabled'   ? item(set::name($lang->workflowapproval->approvalFlow), zget($approvalFlows, $approvalFlow)) : null,
        item(set::name($lang->workflow->desc), html(nl2br($flow->desc)))
    )
);

hr();

history
(
    set::objectID($flow->id)
);

render();
