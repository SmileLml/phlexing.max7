<?php
/**
 * The browse view file of workflowrule module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     workflowrule
 * @link        https://www.zentao.net
 */
namespace zin;

featurebar();

hasPriv('workflowrule', 'create') ? toolbar
(
    item(set(array('icon' => 'plus', 'class' => 'primary', 'data-toggle' => 'modal', 'text' => $lang->workflowrule->create, 'url' => inlink('create'))))
) : null;

$cols  = $config->workflowrule->dtable->fieldList;
$rules = initTableData($rules, $cols, $this->workflowrule);

dtable
(
    set::cols($cols),
    set::data($rules),
    set::userMap($users),
    set::orderBy($orderBy),
    set::sortLink(inlink('browse', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager()),
    set::fixedLeftWidth('0.3')
);

render();
