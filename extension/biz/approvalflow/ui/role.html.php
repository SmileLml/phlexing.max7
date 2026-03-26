<?php
/**
 * The role view file of approval flow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun <sunguangming@easycorp.ltd>
 * @package     approvalflow
 * @link        https://www.zentao.net
 */
namespace zin;

include $app->getModuleRoot() . 'custom/ui/sidebar.html.php';

featureBar();

$canCreateRole = common::hasPriv('approvalflow', 'createRole');
$createItem    = array('text' => $lang->approvalflow->createRole, 'url' => createLink('approvalflow', 'createRole'));
toolbar($canCreateRole ? item(set($createItem + array('class' => 'btn primary', 'icon' => 'plus', 'data-toggle' => 'modal'))) : null);

$cols  = $config->approvalflow->dtable->role->fieldList;
$roles = initTableData($roleList, $cols, $this->approvalflow);

foreach($roles as $role)
{
    $member = '';
    foreach(explode(',', $role->users) as $account)
    {
        if(!$account) continue;
        $member .= zget($users, $account) . ',';
    }
    $member = trim($member, ',');

    $role->member = $member;
}

dtable
(
    set::cols($cols),
    set::data($roles),
    set::userMap($users),
    set::checkable(false)
);
