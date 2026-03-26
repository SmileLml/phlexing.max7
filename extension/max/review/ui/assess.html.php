<?php
/**
 * The assess view file of review module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     review
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;
$sideWidth = '600';

featureBar
(
    to::leading(array(backBtn(set::icon('back'), set::className('primary-outline'), set::url(createLink('review', 'browse', "reviewID=$review->project")), $lang->goback))),
    entityTitle(set::titleClass('text-lg text-clip font-bold'), setID((string)$review->id), set::object($review), set::type('review'), set::title($review->title . ' > ' . zget($lang->baseline->objectList, $review->category)))
);

$reviewModel  = $this->review;
$buildSideBar = function($review, $viewData) use ($sideWidth, $reviewModel)
{
    global $app, $lang;
    $nodes              = array();
    $isTemplate         = !empty($viewData->template) && empty($viewData->doc);
    $reviewData         = json_decode($review->data, true);
    $showWithPageEditor = isset($reviewData['$migrate']) && $reviewData['$migrate'] == 'html';
    if(!empty($viewData->bookID) && !$showWithPageEditor)
    {
        $nodes[] = div
        (
            setID('bookTree'),
            treeEditor(set::items($reviewModel->buildBookTree($viewData->book, $viewData->review, zget($viewData, 'docID', 0))), set::canSplit(false))
        );
    }
    elseif(empty($review->template) && empty($review->doc) && $review->category == 'PP')
    {
        $from      = 'doc';
        $ganttType = $viewData->type;
        $productID = $review->product;
        $projectID = $review->project;
        $reviewID  = $review->id;
        include $app->getModuleRoot() . 'programplan/ui/ganttfields.html.php';

        data('showFields', 'PM,status,deadline');
        $ganttFields['column_text'] = $lang->programplan->ganttBrowseType['gantt'];
        $nodes[] = gantt
        (
            set('ganttLang', $ganttLang),
            set('ganttFields', $ganttFields),
            set('showChart', false),
            set('colsWidth', $sideWidth),
            set('options', $viewData->plans)
        );
    }
    elseif($isTemplate)
    {
        if(!empty($review->data))
        {
            $nodes[] = pageEditor
            (
                set::size('auto'),
                set::readonly(true),
                set::content($review->data)
            );
        }
    }
    else
    {
        $linkedDoc = !empty($viewData->doc) ? $viewData->doc : null;
        if($linkedDoc)
        {
            $contentType = $linkedDoc->contentType;
            $nodes[] = section
            (
                set::title($linkedDoc->title),
                div
                (
                    setClass('article-content'),
                    setID($linkedDoc->contentType == 'markdown' ? 'markdownContent' : null),
                    $contentType === 'doc' ? pageEditor
                    (
                        set::size('auto'),
                        set::readonly(true),
                        set::value(isset($linkedDoc->rawContent) ? $linkedDoc->rawContent : $linkedDoc->content)
                    ) : editor
                    (
                        set::resizable(false),
                        set::markdown($linkedDoc->contentType == 'markdown'),
                        set::readonly(true),
                        set::hideUI(true),
                        set::size('auto'),
                        html($linkedDoc->content)
                    )
                )
            );
        }
    }
    if(!empty($viewData->doc) && !empty($viewData->doc->files)) $nodes[] = fileList(set::files($viewData->doc->files), set::padding(false));
    if(!empty($review->files)) $nodes[] = fileList(set::files($review->files), set::padding(false));

    return $nodes;
};

sidebar
(
    set::width($sideWidth + 10),
    set::maxWidth($sideWidth + 10),
    set::minWidth(0),
    set::toggleBtn(false),
    $buildSideBar($review, $this->view)
);

if($setReviewer) $reviewer = strpos($setReviewer, 'pending-') !== false ? substr($setReviewer, 8) : $setReviewer;

$reviewclHtml = '';
if(!empty($reviewcl))
{
    $reviewclHtml  = "<table class='table bordered condensed reviewcl'>";
    $reviewclHtml .= "<caption class='text-left pb-2'>{$lang->review->reviewcl}</caption>";
    $reviewclHtml .= "<thead><tr><th>{$lang->review->listCategory}</th><th>{$lang->review->listTitle}</th><th>{$lang->review->listResult}</th><th>{$lang->review->opinion}</th></tr></thead>";
    $reviewclHtml .= '<tbody>';
    foreach($reviewcl as $category => $list)
    {
        $reviewclHtml .= "<tr><td rowspan='" . count($list) . "' class='text-center font-bold'>" . zget($categoryList, $category) . '</td>';
        $i = 0;
        foreach($list as $data)
        {
            $i++;
            if($i != 1) $reviewclHtml .= '<tr>';
            $reviewclHtml .= "<td>" . html::a(createLink('reviewcl', 'view', "id=$data->id", '', true), $data->title, '', "title='$data->title' data-toggle='modal' data-type='iframe'") . '</td>';
            $reviewclHtml .= "<td>" . html::radio("issueResult[$data->id]", $lang->review->checkList, '1', "class='issueResult' onchange='toggleOption(this)'", 'block') . '</td>';
            $reviewclHtml .= "<td><textarea name='issueOpinion[$data->id]' id='issueOpinion$data->id' rows='2' class='w-full opinion' readonly></textarea></td>";
            if($i != 1) $reviewclHtml .= '</tr>';
        }
    }
    $reviewclHtml .= '</tbody></table>';
}

panel
(
    setClass('panel-form'),
    form
    (
        set::actions(array()),
        set::labelWidth('100px'),
        setID('reviewForm'),
        !empty($reviewcl) ? formRow
        (
            setID('reviewrc'),
            set::width('full'),
            html($reviewclHtml)
        ) : null,
        $setReviewer ? formGroup
        (
            set::width('1/3'),
            set::label($lang->review->setReviewer),
            set::name('setReviewer'),
            set::value($reviewer),
            set::control('picker'),
            set::items($users)
        ) : null,
        formGroup
        (
            set::width('full'),
            set::label($lang->review->reviewResult),
            set::name('result'),
            set::value(isset($result->result) ? $result->result : 'pass'),
            set::control('radioListInline'),
            set::items($lang->review->resultList)
        ),
        formRow
        (
            formGroup(set::width('1/3'), set::label($lang->review->reviewedDate), set::name('createdDate'), set::value(helper::today()), set::control('datePicker')),
            formGroup
            (
                set::width('1/3'),
                set::label($lang->review->consumed),
                inputControl
                (
                    input(set::name('consumed'), set::value(isset($result->consumed) ? $result->consumed : 0)),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20)
                )
            )
        ),
        formGroup(set::width('full'), set::label($lang->review->finalOpinion), set::name('opinion'), set::value(isset($result->opinion) ? $result->opinion : ''), set::control('editor')),
        formGroup
        (
            set::width('full'),
            set::label($lang->files),
            fileSelector()
        ),
        toolbar(setClass('review-actions toolbar form-actions form-group no-label'), btn(set(array('text' => $lang->save, 'btnType' => 'submit', 'type' => 'primary'))), isset($currentNode->priv) && in_array('revert', $currentNode->priv)  ? btn(set(array('text' => $lang->approval->revert,  'url' => createLink('approval', 'revert', "objectType=review&objectID=$reviewID"),  'innerClass' => 'revert-btn',  'data-toggle' => 'modal'))) : null, isset($currentNode->priv) && in_array('forward', $currentNode->priv) ? btn(set(array('text' => $lang->approval->forward, 'url' => createLink('approval', 'forward', "objectType=review&objectID=$reviewID"), 'innerClass' => 'forward-btn', 'data-toggle' => 'modal'))) : null, isset($currentNode->priv) && in_array('addnode', $currentNode->priv) ? btn(set(array('text' => $lang->approval->addNode, 'url' => createLink('approval', 'addNode', "objectType=review&objectID=$reviewID"), 'innerClass' => 'forward-btn', 'data-toggle' => 'modal'))) : null, hasPriv('approval', 'progress') ? btn(set(array('text' => $lang->approval->progress, 'url' => createLink('approval', 'progress', "approvalID={$approval->id}"), 'data-toggle' => 'modal'))) : null)
    )
);
history(set::objectID((int)$reviewID));
