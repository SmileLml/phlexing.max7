<?php
/**
 * The importldap ui file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current('all'),
    li(searchToggle(set::module('ldap'), set::open($type == 'bysearch'))),
    to::before
    (
       h5($lang->user->importLDAP)
    )
);

$visionItems = array();
$localItems  = array();
$deptItems   = array();
$roleItems   = array();
$groupItems  = array();
$genderItems = array();

$visions = getVisions();
foreach($visions as $visionKey => $visionName)  $visionItems[] = array('text' => $visionName,  'value' => $visionKey);
foreach($localUsers as $userID => $userAccount) $localItems[]  = array('text' => $userAccount, 'value' => $userID);
foreach($depts as $deptID => $deptName)         $deptItems[]   = array('text' => $deptName,    'value' => $deptID);
foreach($roles as $roleCode => $roleName)       $roleItems[]   = array('text' => $roleName,    'value' => $roleCode);
foreach($groups as $groupID => $groupName)      $groupItems[]  = array('text' => $groupName,   'value' => $groupID);
foreach($genders as $genderCode => $genderName) $genderItems[] = array('text' => $genderName,  'value' => $genderCode);

$config->user->importldap->dtable->fieldList['visions']['control']['props']['items']    = $visionItems;
$config->user->importldap->dtable->fieldList['visions']['control']['props']['required'] = true;
$config->user->importldap->dtable->fieldList['visions']['control']['props']['value']    = 'rnd';

$config->user->importldap->dtable->fieldList['link']['control']['props']['items']    = $localItems;
$config->user->importldap->dtable->fieldList['dept']['control']['props']['items']    = $deptItems;
$config->user->importldap->dtable->fieldList['dept']['control']['props']['popWidth'] = 'auto';
$config->user->importldap->dtable->fieldList['role']['control']['props']['items']    = $roleItems;
$config->user->importldap->dtable->fieldList['group']['control']['props']['items']   = $groupItems;
$config->user->importldap->dtable->fieldList['gender']['control']['props']['items']  = $genderItems;

foreach($users as $i => $user) $users[$i]['id'] = $i + 1;

$footToolbar = array('items' => array(array('text' => $lang->import, 'btnType' => 'secondary', 'className' => 'import-btn')));

formBase
(
    setID('importLdap'),
    set::actions(array()),
    dtable
    (
        set::cols($config->user->importldap->dtable->fieldList),
        set::data(array_values($users)),
        set::checkable(true),
        set::footPager(usePager()),
        set::footToolbar($footToolbar),
        set::plugins(array('form'))
    )
);
