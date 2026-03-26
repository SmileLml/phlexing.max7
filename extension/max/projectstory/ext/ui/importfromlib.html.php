<?php
namespace zin;

jsVar('gradeGroup', $gradeGroup);
jsVar('projectID', $projectID);
jsVar('productID', $productID);
jsVar('storyType', $storyType);
jsVar('showGrade', $showGrade);

featureBar
(
    to::leading
    (
        backBtn(set::icon('back'), set::type('secondary'), $lang->goback)
    ),
    inputGroup
    (
        setClass('ml-6'),
        $lang->assetlib->selectLib,
        picker
        (
            set::width(200),
            set::name('fromlib'),
            set::items($libraries),
            set::value($libID),
            set::required(true),
            on::change('changeLib')
        )
    )
);

searchForm
(
    set::module('projectstory'),
    set::simple(true),
    set::show(true)
);

$stories = initTableData($stories, $config->projectstory->dtable->fieldList, $this->projectstory);
$cols    = array_values($config->projectstory->dtable->fieldList);
$data    = array_values($stories);

$footToolbar = array('items' => array(array('text' => $lang->projectstory->import, 'btnType' => 'secondary', 'className' => 'importlib-btn', 'data-formaction' => createLink('projectstory', 'importFromLib', "projectID={$projectID}&productID={$productID}&libID={$libID}&storyType={$storyType}&fromlib={$libID}"))));

dtable(set::id('storyList'), set::cols($cols), set::data($data), set::userMap($users), set::fixedLeftWidth('44%'), set::checkable(hasPriv('projectstory', 'importfromlib')), set::onRenderCell(jsRaw('window.renderCell')), set::orderBy($orderBy), set::sortLink(inlink('importFromLib', "projectID={$projectID}&productID={$productID}&storyType={$storyType}&orderBy={name}_{sortType}")), set::footToolbar($footToolbar), set::footPager(usePager()));
