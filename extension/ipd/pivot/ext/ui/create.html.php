<?php
/**
 * The edit view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

$biPath = $this->app->getModuleExtPath('bi', 'ui');
include $biPath['common'] . 'aclbox.html.php';

$clientLang = $this->app->getClientLang();
$langs      = $config->langs;

$nameTabPanes = array();
$descTabPanes = array();
foreach($langs as $sysLang => $langName)
{
    $currentLang = $sysLang == $clientLang;

    $nameName   = "name[$sysLang]";
    $descName   = "desc[$sysLang]";

    $nameTabPanes[] = tabPane
    (
        set::key('name-' . $sysLang),
        set::title($langName),
        set::active($currentLang),
        formGroup
        (
            set::name($nameName),
            set::required(true)
        )
    );

    $descTabPanes[] = tabPane
    (
        set::key('desc-' . $sysLang),
        set::title($langName),
        set::active($currentLang),
        formGroup(set::control(array('type' => 'textarea', 'rows' => 3)), set::name($descName))
    );
}

formPanel
(
    formGroup
    (
        set::label($lang->pivot->group),
        picker(set::name('group[]'), set::items($groups), set::multiple(true)),
        set::required(true)
    ),
    formRow(formGroup
    (
        set::label($lang->pivot->name),
        set::labelClass('top-11'),
        set::width('full'),
        tabs
        (
            setClass('w-full'),
            set::titleClass('font-thin text-sm'),
            $nameTabPanes
        ),
        set::required(true)
    )),
    formGroup(set::label($lang->bi->driver), picker
    (
        set::name('driver'),
        set::items($lang->bi->driverList),
        set::required(true)
    )),
    formRow(formGroup
    (
        set::label($lang->pivot->desc),
        set::labelClass('top-15'),
        set::width('full'),
        tabs
        (
            setClass('w-full'),
            set::titleClass('font-thin text-sm'),
            $descTabPanes
        )
    )),
    $fnAclBox($lang->pivot->aclList, 'open'),
    set::submitBtnText($lang->save)
);

render();

