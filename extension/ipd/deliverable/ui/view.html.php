<?php
/**
 * The view file of deliverable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     deliverable
 * @link        https://www.zentao.net
 */
namespace zin;

$modelContent = '';
if($deliverable->model)
{
    foreach(explode(',', $deliverable->model) as $model)
    {
        if(!isset($modelList[$model])) continue;
        $modelContent .= zget($modelList, $model) . '</br>';
    }
}

$items[$lang->deliverable->module]       = zget($lang->deliverable->moduleList, $deliverable->module);
$items[$lang->deliverable->method]       = zget($lang->deliverable->methodList, $deliverable->method);
$items[$lang->deliverable->model]        = array('control' => 'html', 'content' => $modelContent);
$items[$lang->deliverable->createdBy]    = zget($users, $deliverable->createdBy) . $lang->at . $deliverable->createdDate;
$items[$lang->deliverable->lastEditedBy] = $deliverable->lastEditedBy ? (zget($users, $deliverable->lastEditedBy) . $lang->at . $deliverable->lastEditedDate) : '';

$operateList = $this->loadModel('common')->buildOperateMenu($deliverable);
$actions     = $operateList['mainActions'];
if(!empty($operateList['suffixActions'])) $actions = array_merge($actions, array(array('type' => 'divider')), $operateList['suffixActions']);

/* 初始化主栏内容。Init sections in main column. */
$sections = array();
$sections[] = setting()
    ->title($lang->deliverable->desc)
    ->control('html')
    ->content($deliverable->desc);

if($deliverable->files)
{
    $sections[] = setting()
    ->control('fileList')
    ->fileTitle($lang->deliverable->abbr->template)
    ->files( $deliverable->files)
    ->object($deliverable)
    ->padding(false);
}

/* 初始化侧边栏标签页。Init sidebar tabs. */
$tabs = array();

/* 基本信息。Legend basic items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->deliverable->basicInfo)
    ->control('datalist')
    ->items($items);

detail
(
    set::urlFormatter(array('{id}' => $deliverable->id)),
    set::sections($sections),
    set::tabs($tabs),
    set::actions(array_values($actions))
);
