<?php
/**
 * The license view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

$tableItems = array();
foreach($lang->admin->property as $key => $name)
{
    $property = zget($ioncubeProperties, $key, '');
    if($key == 'serviceDeadline' && helper::isZeroDate($property)) continue;

    if($key == 'expireDate' and $property == 'All Life') $property = $lang->admin->licenseInfo['alllife'];
    if($key == 'user' and empty($property)) $property = $lang->admin->licenseInfo['nouser'];
    $tableItems[] = item(set::name($name), $property);
}

$buildExtTable = function() use($extProperties, $userCount)
{
    if(empty($extProperties)) return;

    global $lang, $app;

    $trList = array();
    foreach($extProperties as $extCode => $properties)
    {
        if(!is_array($properties)) return array();

        $canManageMember = false;
        $expired         = false;
        if(!empty($properties['user']) && $userCount > $properties['user']) $canManageMember = true;
        if(!empty($properties['expireDate']) && $properties['expireDate'] != 'All Life' && $properties['expireDate'] < date('Y-m-d'))
        {
            $canManageMember = false;
            $expired         = true;
        }

        $link  = $canManageMember ? helper::createLink('admin', 'manageExtMember', "code={$extCode}") : '#';
        $title = $lang->admin->authorUser;
        if(!$canManageMember)
        {
            $title = $lang->admin->disableForCount;
            if($expired) $title = $lang->admin->disableForExpire;
        }

        $trList[] = h::tr
        (
            h::td($lang->admin->extensionList[$extCode]),
            h::td(setClass('text-center'), $properties['startDate']),
            h::td(setClass('text-center'), $properties['expireDate'] == 'All Life' ? $lang->admin->licenseInfo['alllife'] : $properties['expireDate']),
            h::td(setClass('text-center'), empty($properties['user']) ? $lang->admin->licenseInfo['nouser'] : $properties['user']),
            h::td(setClass('text-center'), $app->user->admin ? html(html::a($link, "<i class='icon icon-persons'></i>", '', "data-toggle='modal' title='{$title}'" . ($canManageMember ? '' : " class='disabled'"))) : null)
        );
    }

    return h::table
    (
        setClass('table mt-5'),
        h::caption(setClass('text-left p-3 font-bold'), $lang->admin->solutionExt),
        h::thead
        (
            h::tr
            (
                h::th(setClass('text-left'), $lang->admin->extensionName),
                h::th(setClass('w-32'), $lang->admin->property->startDate),
                h::th(setClass('w-32'), $lang->admin->property->expireDate),
                h::th(setClass('w-32'), $lang->admin->property->user),
                h::th(setClass('w-20'), $lang->actions)
            )
        ),
        h::tbody($trList)
    );
};

panel
(
    detailHeader
    (
        to::prefix(''),
        to::title(entityLabel(set(array('level' => 2, 'text' => $lang->admin->license)))),
        to::suffix
        (
            btn
            (
                setClass('btn primary'),
                set::url(inlink('uploadLicense')),
                setData(array('toggle' => 'modal')),
                $lang->admin->uploadLicense
            )
        )
    ),
    hr(),
    tableData($tableItems),
    $buildExtTable()
);
