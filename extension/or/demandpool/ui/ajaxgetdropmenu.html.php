<?php
/**
 * The ajaxgetdropmenu view file of demandpool module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     demandpool
 * @link        https://www.zentao.net
 */
namespace zin;

$data = array();
foreach($demandpools as $currentPoolID => $pool)
{
    $item = array();
    $item['id']     = $currentPoolID;
    $item['name']   = $pool->name;
    $item['text']   = $pool->name;
    $item['title']  = $pool->name;
    $item['active'] = $poolID == $currentPoolID;
    $item['url']    = sprintf($link, $currentPoolID);
    $item['type']   = 'item';
    $data[] = $item;
}

$json = array();
$json['data']       = $data;
$json['searchHint'] = $lang->searchAB;

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
