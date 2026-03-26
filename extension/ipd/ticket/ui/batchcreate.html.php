<?php
/**
 * The batch create view file of ticket module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     ticket
 * @link        https://www.zentao.net
 */
namespace zin;

$items = $this->config->ticket->form->batchCreate;
$items['module']['items']      = $modules[$productID];
$items['assignedTo']['items']  = $users;
$items['mailto']['items']      = $users;
$items['openedBuild']['items'] = $builds;
$items['module']['value']      = $moduleID;

foreach(array_filter(explode(',', $config->ticket->create->requiredFields)) as $field)
{
    if(isset($items[$field])) $items[$field]['required'] = true;
}
if($tickets) $items['uploadImage'] = array('name' => 'uploadImage', 'label' => '', 'control' => 'hidden', 'hidden' => true);

formBatchPanel
(
    set::title($lang->ticket->batchCreate),
    set::items($items),
    $tickets ? set::data($tickets) : null,
    set::headingActionsClass('flex-auto row-reverse justify-between w-11/12'),
    set::pasteField('title'),
    set::uploadParams('module=ticket&params=' . helper::safe64Encode("productID={$productID}&moduleID={$moduleID}")),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchCreateFields'))
);

render();
