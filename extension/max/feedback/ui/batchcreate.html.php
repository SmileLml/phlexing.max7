<?php
/**
 * The batchcreate view file of feedback module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     feedback
 * @link        https://www.zentao.net
 */
namespace zin;

$formFields = $config->feedback->form->batchcreate;
array_unshift($formFields, array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '32px'));

$showFields     = explode(',', $config->feedback->custom->batchCreateFields);
$requiredFields = array_filter(explode(',', $config->feedback->create->requiredFields));
foreach($requiredFields as $field)
{
    if(strpos(",{$config->feedback->custom->batchCreateFields},", ",{$field},") === false) $showFields[] = $field;
    if(isset($formFields[$field])) $formFields[$field]['required'] = true;
}
$formFields['module']['items'] = $modules;
$formFields['module']['value'] = $moduleID;
$formFields['mailto']['items'] = $users;
if($feedbacks) $formFields['uploadImage'] = array('name' => 'uploadImage', 'label' => '', 'control' => 'hidden', 'hidden' => true);

/* Set Custom fields. */
$customFields = array();
foreach(explode(',', $config->feedback->list->customBatchCreateFields) as $field) $customFields[$field] = $this->lang->feedback->$field;

formBatchPanel
(
    set::title($lang->feedback->batchCreate),
    set::headingActionsClass('flex-auto row-reverse justify-between w-11/12'),
    $feedbacks ? set::data($feedbacks) : null,
    set::pasteField('title'),
    set::uploadParams('module=feedback&params=' . helper::safe64Encode("productID={$productID}&moduleID={$moduleID}")),
    set::customFields(array('list' => $customFields, 'show' => $showFields, 'key' => 'batchCreateFields')),
    set::items(array_values($formFields))
);
