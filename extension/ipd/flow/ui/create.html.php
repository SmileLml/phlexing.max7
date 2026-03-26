<?php
/**
 * The create view file of flow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     flow
 * @link        https://www.zentao.net
 */
namespace zin;
include 'header.html.php';

$fieldList = defineFieldList("{$flow->module}.create");
$fieldList = $this->flow->buildFormFields($fieldList, $fields, $childFields);

formGridPanel
(
    set::title($title),
    set::url($actionURL),
    set::defaultMode('full'),
    set::ajax(array('submitDisabledValue' => false)),
    set::modeSwitcher(false),
    set::fields($fieldList)
);

html($formulaScript);
html($linkageScript);
