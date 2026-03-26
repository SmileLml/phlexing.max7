<?php
/**
 * The batch edit view file of ticket module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     ticket
 * @link        https://www.zentao.net
 */
namespace zin;

$moduleItems = array();
foreach($modules as $productID => $productModules)
{
    $moduleItems[$productID] = array();
    foreach($productModules as $moduleID => $moduleName) $moduleItems[$productID][] = array('text' => $moduleName, 'value' => $moduleID);
}
jsVar('modules', $moduleItems);
jsVar('batchEditTip', $batchEditTip);

$items = $this->config->ticket->form->batchEdit;
$items['product']['items']    = $products;
$items['module']['items']     = array();
$items['module']['control']   = array('control' => 'picker', 'required' => true);
$items['pri']['items']        = $this->lang->ticket->priList;
$items['type']['items']       = $this->lang->ticket->typeList;
$items['assignedTo']['items'] = $users;

foreach(array_filter(explode(',', $config->ticket->edit->requiredFields)) as $field)
{
    if(isset($items[$field])) $items[$field]['required'] = true;
}

formBatchPanel(on::change('[data-name="product"]', 'changeModule'), set::onRenderRow(jsRaw('renderRowData')), set::title($lang->ticket->batchEdit), set::mode('edit'), set::items($items), set::data(array_values($tickets)));

render();
