<?php
/**
 * The batchCreate view file of researchtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hccheng Tang<tanghucheng@easycorp.ltd>
 * @package     researchtask
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

$items = array();

/* Field of title. */
$items[] = array('name' => 'name', 'label' => $lang->programplan->name, 'width' => '240px', 'required' => true);

/* Field of assignedTo. */
$items[] = array('name' => 'PM', 'label' => $lang->programplan->PM, 'control' => 'picker', 'items' => $PMUsers, 'width' => '150px');

/* Field of acl. */
$items[] = array
(
    'name' => 'acl',
    'label' => $lang->marketresearch->acl,
    'control' => array('control' => 'picker', 'required' => true),
    'items' => $lang->marketresearch->stageAcl,
    'width' => '240px'
);

/* Field of begin. */
$items[] = array('name' => 'begin', 'label' => $lang->programplan->begin, 'width' => '180px', 'control' => 'date', 'required' => true);

/* Field of end. */
$items[] = array('name' => 'end', 'label' => $lang->programplan->end, 'width' => '150px', 'control' => 'date', 'required' => true);

/* Field of realBegan. */
$items[] = array('name' => 'realBegan', 'label' => $lang->programplan->realBegan, 'width' => '150px', 'control' => 'date');

/* Field of realEnd. */
$items[] = array('name' => 'realEnd', 'label' => $lang->programplan->realEnd, 'width' => '150px', 'control' => 'date');

/* Field of attributes. */
$items[] = array('name' => 'attributes', 'hidden' => true, 'value' => 'research');

/* Field of type. */
$items[] = array('name' => 'type', 'hidden' => true, 'value' => 'stage');

/* Field of id. */
$items[] = array('name' => 'id', 'hidden' => true);

/* Field of market. */
$items[] = array('name' => 'market', 'hidden' => true, 'value' => $project->market);

formBatchPanel
(
    set::title($title),
    set::items($items),
    set::data(array_values($plans)),
    set::onRenderRow(jsRaw('window.onRenderRow'))
);

/* ====== Render page ====== */
render();
