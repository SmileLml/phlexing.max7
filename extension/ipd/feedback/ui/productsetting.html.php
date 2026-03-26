<?php
/**
 * The productsetting view file of feedback module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     feedback
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->feedback->productSetting), set::titleClass('h3'));
$productList = array();
foreach($productPairs as $productID => $title)
{
    if(!array_key_exists($productID, $products)) continue;;
    $productList[$productID] = new stdclass();
    $productList[$productID]->products  = $productID;
    $productList[$productID]->feedbacks = !empty($productHeadMap[$productID]['feedback']) ? $productHeadMap[$productID]['feedback'] : '';
    $productList[$productID]->tickets   = !empty($productHeadMap[$productID]['ticket']) ? $productHeadMap[$productID]['ticket'] : '';
}

$items = array();
$items[] = array('label' => $lang->productCommon,        'name' => 'products',  'control' => 'picker', 'items' => $products);
$items[] = array('label' => $lang->feedback->head,       'name' => 'feedbacks', 'control' => 'picker', 'items' => $users);
$items[] = array('label' => $lang->feedback->ticketHead, 'name' => 'tickets',   'control' => 'picker', 'items' => $users);

if(empty($productList))
{
    div
    (
        setClass('empty-tip text-center'),
        span(setClass('text-gray'), $lang->feedback->productSettingNoProduct)
    );
}
else
{
    formBatchPanel
    (
        set::data(array_values($productList)),
        set::maxRows(count($productList)),
        set::items($items)
    );
}
