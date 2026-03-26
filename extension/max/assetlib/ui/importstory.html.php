<?php
namespace zin;

jsVar('libID', $libID);
jsVar('projectID', $projectID);
jsVar('showGrade', $showGrade);
jsVar('gradeGroup', $gradeGroup);

featureBar(to::leading
(
    backBtn(set::icon('back'), set::type('secondary'), $lang->goback)
), inputGroup
(
    setClass('ml-6'),
    $lang->assetlib->selectProject,
    picker
    (
        set::width(200),
        set::name('fromProject'),
        set::items($allProject),
        set::value($projectID),
        set::required(true),
        on::change('[name=fromProject]')->call('changeProject(event)')
    )
), $project->hasProduct ? inputGroup
(
    setClass('ml-6'),
    $lang->assetlib->selectProduct,
    picker
    (
        set::width(200),
        set::name('fromProduct'),
        set::items($products),
        set::value($productID),
        set::required(true),
        on::change()->call('changeProduct(event)')
    )
) : null);

searchForm
(
    set::module('assetStory'),
    set::simple(true),
    set::show(true)
);

$footToolbar['items'][] = array('text' => $lang->assetlib->import, 'class' => 'btn btn-caret size-sm', 'btnType' => 'secondary', 'data-formaction' => inlink('importStory', "libID={$libID}&projectID={$projectID}&productID={$productID}"));

$stories = initTableData($stories, $config->assetlib->dtable->importStory->fieldList, $this->assetlib);
array_map(function($story)
{
    $story->plan = $story->planTitle;
}, $stories);
$cols = array_values($config->assetlib->dtable->importStory->fieldList);
$data = array_values($stories);

dtable(set::id('stories'), set::cols($cols), set::data($data), set::userMap($users), set::fixedLeftWidth('44%'), set::checkable(true), set::onRenderCell(jsRaw('window.renderCell')), set::orderBy($orderBy), set::sortLink(inlink('importStory', "libID={$libID}&projectID={$projectID}&productID={$productID}&orderBy={name}_{sortType}")), set::footToolbar($footToolbar), set::footPager(usePager()));
