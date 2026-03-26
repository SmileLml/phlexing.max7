<?php
/**
 * The project view file of workflowgroup module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun <sunguangming@chandao.com>
 * @package     workflowgroup
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('buildinLang', $lang->workflow->buildin);

featurebar();

hasPriv('workflowgroup', 'create') ? toolbar
(
    item(set(array('icon' => 'plus', 'class' => 'primary', 'data-toggle' => 'modal', 'text' => $lang->workflowgroup->createProject, 'url' => inlink('create', 'type=project'))))
) : null;

$cols = $config->workflowgroup->dtable->project->fieldList;
$cols['actions']['list']['delete']['data-confirm'] = sprintf($lang->workflowgroup->notice->confirmDelete, $lang->projectCommon);

$data = initTableData($groups, $cols, $this->workflowgroup);
foreach($data as $workflowgroup)
{
    if($workflowgroup->main == '0') continue;
    foreach($workflowgroup->actions as $i => $action)
    {
        if(in_array($action['name'], array('release', 'deactivate', 'edit', 'delete'))) $workflowgroup->actions[$i]['disabled'] = true;
    }
}

dtable
(
    set::cols($cols),
    set::data($data),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::sortLink(createLink('workflowgroup', 'project', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::footPager(usePager())
);
