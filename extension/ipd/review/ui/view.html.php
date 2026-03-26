<?php
/**
 * The view view file of review module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     review
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

$reviewData         = json_decode($review->data, true);
$showWithPageEditor = isset($reviewData['$migrate']) && $reviewData['$migrate'] == 'html';
$sections = array();
if(!empty($bookID) && !$showWithPageEditor)
{
    $nodeTree   = $this->review->buildBookTree($book, $review, isset($docID) ? $docID : 0);
    $sections[] = setting()->control('treeEditor')->items($nodeTree)->canSplit(false);
}
elseif(empty($review->template) && empty($review->doc) && $review->category == 'PP')
{
    $from       = 'doc';
    $ganttType  = 'gantt';
    $productID  = $review->product;
    $projectID  = $review->project;
    include $app->getModuleRoot() . 'programplan/ui/ganttfields.html.php';

    data('showFields', $this->config->programplan->ganttCustom->ganttFields);
    $ganttFields['column_text'] = $lang->programplan->ganttBrowseType['gantt'];
    $sections[] = array('control' => 'gantt', 'ganttLang' => $ganttLang, 'ganttFields' => $ganttFields, 'showChart' => true, 'colsWidth' => '500', 'options' => $plans, 'height' =>
 250);
}
elseif(!empty($review->template) && empty($doc))
{
    if(!empty($review->data)) $sections[] = setting()->control('pageEditor')->content($review->data)->readonly(true);
}
else
{
    if(!empty($doc))
    {
        if($doc->contentType == 'doc')
        {
            $sections[] = setting()->control('pageEditor')
                ->content(isset($doc->rawContent) ? $doc->rawContent : $doc->content)
                ->readonly(true);
        }
        else
        {
            $sections[] = setting()->title($doc->title)
                ->control('html')
                ->content($doc->content)
                ->id($doc->contentType == 'markdown' ? 'markdownContent' : null);
        }
    }

    if(!empty($doc) && !empty($doc->files)) $sections[] = array('control' => 'fileList', 'files' => $doc->files, 'showDelete' => false, 'object' => $doc, 'padding' => false);
}
if(!empty($review->files)) $sections[] = array('control' => 'fileList', 'files' => $review->files, 'showDelete' => false, 'object' => $review, 'padding' => false);

$basicItems = array();
$basicItems[$lang->review->object]      = zget($lang->baseline->objectList, $review->category);
$basicItems[$lang->review->version]     = $review->version;
$basicItems[$lang->review->status]      = zget($lang->review->statusList, $review->status);
$basicItems[$lang->review->reviewedBy]  = $review->reviewedBy ? implode(' ', array_map(function($account) use ($users){return zget($users, $account);}, explode(',', str_replace(' ', '', $review->reviewedBy)))) : '';
$basicItems[$lang->review->reviewer]    = array();
$basicItems[$lang->review->auditedBy]   = zget($users, $review->auditedBy);
$basicItems[$lang->review->deadline]    = helper::isZeroDate($review->deadline) ? '' : substr($review->deadline, 0, 19);
$basicItems[$lang->review->createdBy]   = zget($users, $review->createdBy);
$basicItems[$lang->review->createdDate] = $review->createdDate;
$basicItems[$lang->review->reviewer]    = array
(
    'children' => wg(div
    (
        setClass('row gap-2 flex-wrap'),
        array_values(array_map(function($account, $resultList) use($users)
        {
            $class = (in_array('', $resultList)) ? '' : 'text-gray';
            return span(setClass("mr-1 $class"), zget($users, $account));
        }, array_keys($reviewerResult), array_values($reviewerResult)))
    ))
);
$tabs[] = setting()
    ->group('basic')
    ->title($lang->review->basicInfo)
    ->control('datalist')
    ->items($basicItems)
    ->labelWidth(100);

if(!isset($pendingReviews[$review->id])) unset($config->review->actions->view['mainActions']['assess']);
$actions    = $review->deleted ? array() : $this->loadModel('common')->buildOperateMenu($review);
$hasDivider = !empty($actions['mainActions']) && !empty($actions['suffixActions']);
if(!empty($actions)) $actions = array_merge($actions['mainActions'], $hasDivider ? array(array('type' => 'divider')) : array(), $actions['suffixActions']);

detail
(
    set::object($review),
    set::objectType('review'),
    set::sections($sections),
    set::tabs($tabs),
    set::urlFormatter(array('{id}' => $review->id, '{approval}' => $approval->id)),
    set::actions(array_values($actions))
);
