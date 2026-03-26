<?php
/**
 * The batchCreate view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hucheng Tang <tanghucheng@easycorp.ltd>
 * @package     demand
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();

/* Field of id. */
$items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '32px');

/* Field of title. */
$items[] = array( 'name' => 'title', 'label' => $lang->demand->title, 'width' => '240px', 'required' => true, 'control' => 'colorInput');

/* Field of spec. */
$items[] = array( 'name' => 'spec', 'label' => $lang->demand->spec, 'width' => '240px', 'control' => 'textarea');

/* Field of source. */
$items[] = array('name' => 'source', 'label' => $lang->demand->source, 'control' => 'picker', 'items' => $lang->demand->sourceList, 'value' => '', 'width' => '160px', 'ditto' => true);

/* Field of verify. */
$items[] = array( 'name' => 'verify', 'label' => $lang->demand->verify, 'width' => '240px', 'control' => 'textarea');

/* Field of source. */
$items[] = array('name' => 'category', 'label' => $lang->demand->category, 'control' => 'picker', 'items' => $lang->demand->categoryList, 'value' => '', 'width' => '160px', 'ditto' => true);

/* Field of pri. */
$items[] = array('name' => 'pri', 'label' => $lang->demand->pri, 'control' => array('control' => 'priPicker', 'required' => true), 'items'=> $lang->demand->priList, 'value' => 3, 'width' => '100px', 'ditto' => true);

/* Field of assignedTo. */
$items[] = array('name' => 'assignedTo', 'label' => $lang->demand->assignedTo, 'control' => 'picker', 'items' => $assignToList, 'value' => '', 'width' => '160px', 'ditto' => true);

/* Field of product. */
$items[] = array('name' => 'product', 'label' => $lang->demand->product, 'control' => 'picker', 'items' => $products, 'value' => '', 'multiple' => true, 'width' => '160px');

/* Field of duration. */
$items[] = array('name' => 'duration', 'label' => $lang->demand->duration, 'control' => 'picker', 'items' => $lang->demand->durationList, 'value' => '', 'width' => '160px', 'ditto' => true);

/* Field of BSA. */
$items[] = array('name' => 'BSA', 'label' => $lang->demand->BSA, 'control' => 'picker', 'items' => $lang->demand->bsaList, 'value' => '', 'width' => '160px', 'ditto' => true);

/* Field of keywords. */
$items[] = array('name' => 'keywords', 'label' => $lang->demand->keywords, 'items' => $lang->demand->categoryList, 'value' => '', 'width' => '160px');

if($demands) $items[] = array('name' => 'uploadImage', 'label' => '', 'control' => 'hidden', 'hidden' => true);

formBatchPanel
(
    set::title($lang->demand->batchCreate),
    empty($demands) ? null : set::data($demands),
    set::uploadParams('module=demand&params=' . helper::safe64Encode("poolID=$poolID&demandID=$demandID")),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchCreateFields')),
    set::pasteField('title'),
    set::items($items)
);
