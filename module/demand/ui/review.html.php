<?php
/**
 * The review view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tang Hucheng<tanghucheng@easycorp.ltd>
 * @package     demand
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

$fields = useFields('demand.review');

$formTitle = div
(
    setClass('flex items-center pb-3'),
    div($lang->demand->review),
    entityLabel
    (
        set::level(1),
        setClass('pl-2'),
        set::entityID($demand->id),
        set::reverse(true),
        span($demand->title)
    )
);

formPanel($formTitle, set::fields($fields), on::change('[name="result"]', 'changeResult'), on::change('[name="closedReason"]', 'changeReason'));

history(setClass('panel-form size-lg'));
