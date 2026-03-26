<?php
/**
 * The audit view file of review module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     review
 * @link        https://www.zentao.net
 */
namespace zin;

$sideWidth = '550';

div
(
    setClass('detail-header row gap-2 items-center flex-none'),
    backBtn
    (
        setClass('mr-2 size-md primary-outline'),
        set::icon('back'),
        set::text($lang->goback)
    ),
    entityTitle(setClass('min-w-0'), set::id($review->id), set::object($review), set::title($review->title), set::titleClass('text-lg text-clip font-bold'), set::type('review'), set::joinerClass('text-lg'))
);

$reviewData         = json_decode($review->data, true);
$showWithPageEditor = isset($reviewData['$migrate']) && $reviewData['$migrate'] == 'html';
jsVar('showWithPageEditor', $showWithPageEditor);

$articleContent = array();
$docID          = isset($doc->id) ? $doc->id : 0;
$bookID         = isset($bookID)  ? $bookID : 0;
$isTemplate     = !empty($review->template) && empty($review->doc);
if(empty($review->template) && empty($review->doc) && $review->category == 'PP')
{
    $from      = 'doc';
    $ganttType = 'gantt';
    $productID = $review->product;
    $projectID = $review->project;
    include $app->getModuleRoot() . 'programplan/ui/ganttfields.html.php';

    data('showFields', 'PM,status,deadline');
    $ganttFields['column_text'] = $lang->programplan->ganttBrowseType['gantt'];
    $articleContent[] = gantt
    (
        set('ganttLang', $ganttLang),
        set('ganttFields', $ganttFields),
        set('options', $plans),
        set('showChart', false),
        set('colsWidth', $sideWidth - 40),
        set('height', '300')
    );
}
elseif($isTemplate)
{
    $articleContent[] = empty($review->data) ? null : pageEditor
    (
        set::size('auto'),
        set::readonly(true),
        set::value($review->data)
    );
}
else
{
    if(isset($doc) and $doc)
    {
        $articleContent[] = div(setClass('text-md py-1 font-bold'), $doc->title);
        $articleContent[] = div
            (
                setClass('detail-content article-content'),
                $doc->contentType === 'doc' ? pageEditor
                (
                    set::size('auto'),
                    set::readonly(true),
                    set::value(isset($doc->rawContent) ? $doc->rawContent : $doc->content)
                ) : editor
                (
                    set::resizable(false),
                    set::markdown($doc->contentType == 'markdown'),
                    set::readonly(true),
                    set::hideUI(true),
                    set::size('auto'),
                    html($doc->content)
                )
            );
    }
    elseif(isset($template) and (!isset($doc) or !$doc))
    {
        $articleContent[] = pageEditor
        (
            set::size('auto'),
            set::readonly(true),
            set::value(empty($template->rawContent) ? $template->content : $template->rawContent)
        );
    }
}

$reviewclContent = array();
if($cmcl)
{
    $cmclTrs = array();
    foreach($cmcl as $category => $list)
    {
        $listTrs = array();
        $i       = 0;
        foreach($list as $data)
        {
            $listData = array();
            $listData[] = h::td
            (
                set::title(zget($items, $data->title)),
                zget($items, $data->title)
            );
            $listData[] = h::td(a(set::href(createLink('cmcl', 'view', "id=$data->id")), set::title($data->contents), $data->contents));
            $listData[] = h::td
            (
                radioList
                (
                    setClass('issueResult'),
                    on::change('window.resultChange'),
                    set::name('issueResult[' . $data->id . ']'),
                    set::inline(true),
                    set::items($lang->review->checkList),
                    set::value('1')
                )
            );
            $listData[] = h::td
            (
                setClass('issue-opintion'),
                textarea
                (
                    set::name("issueOpinion[{$data->id}]"),
                    set::value(''),
                    set::disabled(true),
                    set::rows('1')
                ),
                inputGroup
                (
                    setClass('opinionDate hidden mt-2'),
                    span
                    (
                        setClass('nowrap content-center mr-1'),
                        $lang->review->opinionDate
                    ),
                    datePicker(set::name("opinionDate[$data->id]"))
                )
            );
            $listTrs[] = $i == 0 ? $listData : h::tr($listData);
            $i ++;
        }
        $cmclTrs[] = h::tr
        (
            h::td
            (
                setClass('text-center'),
                set::rowspan(count($list)),
                h::strong(zget($typeList, $category))
            ),
            $listTrs
        );
    }
    $reviewclContent[] = panel
    (
        setID('reviewcl'),
        setClass('w-full overflow-y-auto'),
        div(setClass('text-md py-1 font-bold'), $lang->review->reviewcl),
        div
        (
            setClass('detail-content'),
            h::table(setClass('table bordered'), h::tr
            (
                h::td
                (
                    setClass('text-center w-24'),
                    $lang->review->listCategory
                ),
                h::td
                (
                    setClass('text-center w-32'),
                    $lang->review->listItem
                ),
                h::td
                (
                    setClass('text-center'),
                    $lang->review->listTitle
                ),
                h::td
                (
                    setClass('text-center w-24'),
                    $lang->review->listResult
                ),
                h::td
                (
                    setClass('text-center w-44'),
                    $lang->review->opinion
                )
            ), $cmclTrs)
        )
    );
}

$reviewclContent[] = panel
(
    setClass(empty($reviewcl) ? '' : 'review-footer'),
    formGroup
    (
        setClass('mb-3'),
        set::label($lang->review->auditResult),
        radioList
        (
            on::change('window.auditResultChange'),
            set::name('result'),
            set::inline(true),
            set::items($lang->review->auditResultList),
            set::value(isset($result->resule) ? $result->resule : 'pass')
        )
    ),
    formRow
    (
        setClass('mb-3'),
        formGroup
        (
            setClass('w-1/2'),
            set::label($lang->review->auditedDate),
            set::control('datePicker'),
            set::name('createdDate'),
            set::value(helper::today())
        ),
        formGroup
        (
            setClass('w-1/2'),
            set::label($lang->review->consumed),
            inputControl
            (
                input
                (
                    set::name('consumed'),
                    set::value(isset($result->consumed) ? $result->consumed : 0)
                ),
                to::suffix('h')
            )
        )
    ),
    formGroup
    (
        setClass('mb-3'),
        set::label($lang->review->auditOpinion),
        set::control('editor'),
        set::name('opinion'),
        set::value(isset($result->opinion) ? $result->opinion : '')
    ),
    formRow
    (
        setClass('form-actions'),
        button(setClass('btn primary'), set::type('primary'), set::btnType('submit'), $lang->save)
    )
);

div
(
    setClass('row gap-4 mt-2'),
    div
    (
        width("{$sideWidth}px"),
        panel(setID('reviewRow'), setClass('overflow-y-auto'), (
            setID('bookTree')
        ), div
        (
            $articleContent
        ), !empty($review->files) ? fileList(set::files($review->files), set::padding(false)) : null, isset($doc) ? fileList(set::files($doc->files), set::padding(false)) : null)
    ),
    div
    (
        setID('issueList'),
        setClass('col'),
        form
        (
            set::actions(array()),
            $reviewclContent
        ),
        div
        (
            setClass('mt-4'),
            history(set::objectID($review->id))
        )
    )
);

pageJS(<<<JAVASCRIPT
$(function()
{
    if($bookID && !showWithPageEditor) loadCurrentPage({url: $.createLink('review', 'book', 'bookID=' + $bookID + '&reviewID=' + $review->id + '&docID' + $docID), selector: '#bookTree', partial: true});
});
JAVASCRIPT
);

render();
