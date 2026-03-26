<?php
/**
 * The steps view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;

include 'nav.html.php';

$steps = initTableData($steps, $config->deploy->dtable->steps->fieldList);
panel
(
    div(set::id('deployMenu'), setClass('mb-2'), $headers, hasPriv('deploy', 'manageStep') ? btn(set(array
    (
        'type' => 'primary pull-right z-10 deploy manage-btn',
        'text' => $lang->deploy->manageStep,
        'url'   => createLink('deploy', 'manageStep', "deployID={$deploy->id}")
    ))) : null),
    dtable
    (
        set::userMap($users),
        set::cols($config->deploy->dtable->steps->fieldList),
        set::data($steps),
        set::footPager(usePager())
    )
);
