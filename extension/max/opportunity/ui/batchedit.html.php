<?php
/**
 * The batchedit view file of opportunity module of ZenTaoPMS.
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

/* Field of name. */
$items[] = array( 'name' => 'name', 'label' => $lang->opportunity->name, 'required' => true, 'control' => 'input');

/* Field of source. */
$items[] = array('name' => 'source', 'label' => $lang->opportunity->source, 'control' => 'picker', 'items' => $lang->opportunity->sourceList, 'width' => '200px');

/* Field of impact. */
$items[] = array('name' => 'impact', 'label' => $lang->opportunity->impact, 'control' => array('control' => 'picker', 'required' => true), 'items' => $lang->opportunity->impactList, 'width' => '90px');

/* Field of chance. */
$items[] = array('name' => 'chance', 'label' => $lang->opportunity->chance, 'control' => array('control' => 'picker', 'required' => true), 'items' => $lang->opportunity->chanceList, 'width' => '90px');

/* Field of ratio. */
$items[] = array('name' => 'ratio', 'label' => $lang->opportunity->ratio, 'control' => 'input', 'readonly' => true, 'width' => '90px');

/* Field of pri. */
$items[] = array('name' => 'pri', 'label' => $lang->opportunity->pri, 'control' => 'priPicker', 'readonly' => true, 'width' => '90px');

formBatchPanel
(
    on::change('[data-name=impact]', 'computeIndex'),
    on::change('[data-name=chance]', 'computeIndex'),
    set::title($lang->opportunity->batchEdit),
    set::mode('edit'),
    set::data(array_values($opportunities)),
    set::items($items)
);
