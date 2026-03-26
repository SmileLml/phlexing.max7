<?php
/**
 * The view file of cm module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Qiyu Xie <xieqiyu@chandao.com>
 * @package     cm
 * @version     $Id$
 * @link        https://www.zentao.net
 */
namespace zin;

$sections = array();
$baselineData       = json_decode($baseline->data, true);
$showWithPageEditor = isset($baselineData['$migrate']) && $baselineData['$migrate'] == 'html';
if(!empty($bookID) && !$showWithPageEditor)
{
    $nodeTree   = $this->review->buildBookTree($book, $baseline, isset($docID) ? $docID : 0);
    $sections[] = setting()->control('treeEditor')->items($nodeTree)->canSplit(false);
}
elseif(empty($baseline->template) && empty($baseline->doc) && $baseline->category == 'PP')
{
    $from       = 'doc';
    $ganttType  = 'gantt';
    $productID  = $baseline->product;
    $projectID  = $baseline->project;
    include $app->getModuleRoot() . 'programplan/ui/ganttfields.html.php';

    data('showFields', $this->config->programplan->ganttCustom->ganttFields);
    $ganttFields['column_text'] = $lang->programplan->ganttBrowseType['gantt'];
    $sections[] = array('control' => 'gantt', 'ganttLang' => $ganttLang, 'ganttFields' => $ganttFields, 'showChart' => true, 'colsWidth' => '500', 'options' => $plans, 'height' =>
 250);
}
elseif(!empty($baseline->template) && empty($doc))
{
    if(!empty($baseline->data)) $sections[] = setting()->control('pageEditor')->content($baseline->data)->readonly(true);
}
else
{
    if(isset($doc) and $doc)
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
if(!empty($baseline->files)) $sections[] = array('control' => 'fileList', 'files' => $baseline->files, 'showDelete' => false, 'object' => $baseline, 'padding' => false);

$basicItems = array();
$basicItems[$lang->cm->object]      = zget($lang->baseline->objectList, $baseline->category);
$basicItems[$lang->cm->title]       = $baseline->title;
$basicItems[$lang->cm->version]     = $baseline->version;
$basicItems[$lang->cm->createdBy]   = zget($users, $baseline->createdBy);
$basicItems[$lang->cm->createdDate] = $baseline->createdDate;
$tabs[] = setting()
    ->group('basic')
    ->title($lang->cm->basicInfo)
    ->control('datalist')
    ->items($basicItems)
    ->labelWidth(100);

detail
(
    set::object($baseline),
    set::objectType('cm'),
    set::sections($sections),
    set::tabs($tabs)
);
