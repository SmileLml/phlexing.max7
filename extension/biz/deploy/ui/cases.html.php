<?php
/**
 * The cases view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;

include 'nav.html.php';

$footToolbar = hasPriv('deploy', 'batchUnlinkCases') ? array('items' => array(array('text' => $lang->deploy->unlinkCase, 'className' => 'batch-btn ajax-btn', 'data-url' => createLink('deploy', 'batchUnlinkCases', "deployID={$deploy->id}")))) : null;

$config->deploy->dtable->cases->fieldList['actions']['list']['unlinkCase']['url'] = array('module' => 'deploy',   'method' => 'unlinkCase', 'params' => "deploy={$deploy->id}&id={id}");

$tableData = initTableData($cases, $config->deploy->dtable->cases->fieldList);

panel
(
    div
    (
        set::id('deployMenu'),
        setClass('mb-2'),
        $headers,
        hasPriv('deploy', 'linkCases') ? btn(set(array
        (
            'type' => 'primary pull-right z-10 deploy manage-btn',
            'text' => $lang->deploy->linkCases,
            'url'   => createLink('deploy', 'linkCases', "deployID={$deploy->id}")
        ))) : null
    ),
    dtable
    (
        set::cols($config->deploy->dtable->cases->fieldList),
        set::data($tableData),
        set::checkable('true'),
        set::footToolbar($footToolbar)
    )
);
