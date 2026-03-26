<?php
/**
 * The managestep view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;

$app->rawMethod = 'steps';
include 'nav.html.php';
panel
(
    div
    (
        set::id('deployMenu'),
        setClass('flex justify-between mb-2'),
        $headers
    ),
    formPanel
    (
        stepsEditor
        (
            set::name('title'),
            set::expectsName('content'),
            set::stepText($lang->deploy->title),
            set::expectText($lang->deploy->content),
            set::expectDisabled(false),
            $stepGroups ? set::data(array_values($stepGroups)) : null,
            set::postDataID(true)
        )
    )
);
