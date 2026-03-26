<?php
/**
 * The ajaxgetdropmenu view file of market module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     market
 * @link        https://www.zentao.net
 */
namespace zin;

/* 旧页面获取旧的1.5级导航。*/
if(in_array("{$module}-{$method}", $config->index->oldPages))
{
    include '../view/ajaxgetdropmenu.html.php';
    return;
}

$data = array();
foreach($marketPairs as $id => $name)
{
    $item = array();
    $item['id']     = $id;
    $item['name']   = $name;
    $item['text']   = $name;
    $item['title']  = $name;
    $item['active'] = $marketID == $id;
    $item['keys']   = zget($marketsPinYin, $name, '');
    $item['url']    = sprintf($link, $id);
    $item['type']   = 'item';
    $data[] = $item;
}

$json = array();
$json['data']       = $data;
$json['searchHint'] = $lang->searchAB;

renderJson($json);
