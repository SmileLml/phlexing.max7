<?php
/**
 * The template view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar(set::current('all'));

toolbar
(
    hasPriv('project', 'createTemplate') ? item(set(array
    (
        'icon'  => 'plus',
        'text'  => $lang->project->createTemplateAbbr,
        'class' => 'primary create-project-btn',
        'url'   => createLink('project', 'createTemplate'),
    ))) : null
);

$config->project->template->dtable->fieldList['workflowGroup']['map'] = $workflowGroups;

$cols  = $this->loadModel('datatable')->getSetting('project', 'template');
$datas = initTableData($templates, $cols, $this->project);

$cols['status']['statusMap']['doing']= $lang->project->inUse;

dtable
(
    set::userMap($users),
    set::cols($cols),
    set::data($datas),
    set::footPager(usePager()),
    set::orderBy($orderBy),
    set::sortLink(createLink('project', 'template', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::customCols(true)
);

render();
