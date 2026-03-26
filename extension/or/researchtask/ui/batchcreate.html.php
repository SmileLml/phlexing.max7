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

/* Field of id. */
$items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '32px');

/* Field of title. */
$items[] = array('name' => 'name', 'label' => $lang->researchtask->name, 'width' => '240px', 'required' => true, 'control' => 'colorInput');

/* Field of assignedTo. */
$items[] = array('name' => 'assignedTo', 'label' => $lang->researchtask->assignedTo, 'control' => 'picker', 'items' => $members, 'ditto' => true, 'width' => '128px'); 

/* Field of estimate. */
$items[] = array
(
    'name' => 'estimate', 
    'label' => $lang->researchtask->estimateAB, 
    'width' => '100px',
    'control' => array('type' => 'inputControl', 'suffix' => $lang->researchtask->suffixHour, 'suffixWidth' => 20 ), 
); 

/* Field of estStarted. */
$items[] = array('name' => 'estStarted', 'label' => $lang->researchtask->estStarted, 'width' => '150px', 'control' => 'date', 'ditto' => true);

/* Field of deadline. */
$items[] = array('name' => 'deadline', 'label' => $lang->researchtask->deadline, 'width' => '150px', 'control' => 'date', 'ditto' => true);

/* Field of desc. */
$items[] = array('name' => 'desc', 'label' => $lang->researchtask->desc, 'control' => 'textarea', 'width' => '240px');

/* Field of pri. */
$items[] = array
(
    'name' => 'pri', 
    'label' => $lang->researchtask->pri, 
    'control' => 'priPicker', 
    'width' => '100px', 
    'value' => 3,
    'items' => $lang->researchtask->priList, 
    'ditto' => true
);

/* Field of type. */
$items[] = array('name' => 'type', 'hidden' => true, 'value' => 'research');

/* Field of execution. */
$items[] = array('name' => 'execution', 'hidden' => true, 'value' => $execution->id);

/* Field of project. */
$items[] = array('name' => 'project', 'hidden' => true, 'value' => $execution->project);

/* Field of parent. */
$items[] = array('name' => 'parent', 'hidden' => true, 'value' => $parent);

formBatchPanel(set::title($title), set::pasteField('name'), set::headingActionsClass('flex-auto row-reverse'), set::items($items));

/* ====== Render page ====== */
render();
