<?php
/**
 * The deliverable view file of workflowgroup module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@chandao.com>
 * @package     workflowgroup
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('model',         "{$workflowGroup->projectType}_{$workflowGroup->projectModel}");
jsVar('deliverables',  $deliverables);
jsVar('sprintTips',    $lang->workflowgroup->sprintTips);
jsVar('requiredLabel', $lang->workflowgroup->required);

$items = array();
$items['key']         = array('name' => 'key',         'control' => 'hidden',  'hidden' => true);
$items['object']      = array('name' => 'object',      'label' => $lang->workflowgroup->object,      'control' => 'static', 'width' => '120px');
$items['whenClosed']  = array('name' => 'whenClosed',  'label' => $lang->workflowgroup->whenClosed,  'control' => 'hidden', 'width' => '120px', 'tip' => $lang->workflowgroup->whenCreatedTips);

formBatchPanel
(
    set::title($title),
    set::items($items),
    set::mode('edit'),
    set::onRenderRow(jsRaw('renderRowData')),
    set::data(array_values($deliverable)),
    set::minRows(1),
    on::click('[data-name^=deliverable]', 'getDeliverables')
);

query('.form-actions')->before(html("<div class='p-2'>{$lang->workflowgroup->deliverableTips}</div>"));
