<?php
/**
 * The batchcreate view file of opportunity module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     opportunity
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();

/* Field of ID. */
$items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '32px');

/* Field of execution. */
if(!empty($project->multiple)) $items[] = array('name' => 'execution', 'label' => $lang->opportunity->execution, 'control' => 'picker', 'items' => $executions, 'value' => isset($executionID) ? $executionID : '', 'width' => '200px');

/* Field of name. */
$items[] = array( 'name' => 'name', 'label' => $lang->opportunity->name, 'required' => true, 'control' => 'input');

/* Field of source. */
$items[] = array('name' => 'source', 'label' => $lang->opportunity->source, 'control' => 'picker', 'items' => $lang->opportunity->sourceList, 'value' => '', 'width' => '200px');

/* Field of impact. */
$items[] = array('name' => 'impact', 'label' => $lang->opportunity->impact, 'control' => array('control' => 'picker', 'required' => true), 'items' => $lang->opportunity->impactList, 'value' => 3, 'width' => '90px');

/* Field of chance. */
$items[] = array('name' => 'chance', 'label' => $lang->opportunity->chance, 'control' => array('control' => 'picker', 'required' => true), 'items' => $lang->opportunity->chanceList, 'value' => 3, 'width' => '90px');

/* Field of ratio. */
$items[] = array('name' => 'ratio', 'label' => $lang->opportunity->ratio, 'control' => 'input', 'readonly' => true, 'value' => 9, 'width' => '90px');

/* Field of pri. */
$items[] = array('name' => 'pri', 'label' => $lang->opportunity->pri, 'control' => 'priPicker', 'readonly' => true, 'value' => 'middle', 'width' => '90px');

/* Field of desc. */
$items[] = array('name' => 'desc', 'label' => $lang->opportunity->desc, 'control' => 'textarea', 'value' => '', 'width' => '150px');

formBatchPanel
(
    on::change('[data-name=impact]', 'computeIndex'),
    on::change('[data-name=chance]', 'computeIndex'),
    set::title($lang->opportunity->batchCreate),
    set::pasteField('name'),
    set::items($items)
);
