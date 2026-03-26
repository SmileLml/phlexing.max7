<?php
/**
 * The batch operate view file of flow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     flow
 * @link        https://www.zentao.net
 */
namespace zin;
include 'header.html.php';

if($action->batchMode == 'different')
{
    $items = $this->flowZen->buildBatchFormItems($fields, 'off');

    formBatchPanel
    (
        set::mode('edit'),
        set::title($title),
        set::url($actionURL),
        set::items($items),
        set::data(array_values($dataList))
    );
}
else
{
    $fieldList = defineFieldList("{$flow->module}.batchOperate");
    $fieldList = $this->flow->buildFormFields($fieldList, $fields);

    $fieldList->field('dataIDList')->value(implode(',', $dataList))->hidden();

    formGridPanel
    (
        set::title($title),
        set::url($actionURL),
        set::defaultMode('full'),
        set::modeSwitcher(false),
        set::fields($fieldList)
    );
}

html($formulaScript);
