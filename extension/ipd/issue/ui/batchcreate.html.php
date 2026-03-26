<?php
/**
 * The batchCreate view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();

/* Field of id. */
$items[] = array('name' => 'id', 'label' => $lang->issue->id, 'control' => 'index', 'width' => '32px');
foreach($config->issue->form->batchcreate as $fieldName => $fieldItem)
{
    if($fieldName == 'execution')
    {
        $fieldItem['items'] = $executions;
        $fieldItem['value'] = isset($executionID) ? $executionID : '';
        if(empty($project->multiple)) $fieldItem['class'] = 'hidden';
    }
    if($fieldName == 'pri') $fieldItem['value'] = '3';
    if($fieldName == 'assignedTo') $fieldItem['items'] = $teamMembers;
    if($fieldName == 'owner')
    {
        $fieldItem['items'] = $teamMembers;
        $fieldItem['value'] = $app->user->account;
    }
    $items[] = $fieldItem;
}

formBatchPanel
(
    set::title($lang->issue->batchCreate),
    set::items($items)
);
