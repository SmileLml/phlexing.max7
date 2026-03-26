<?php
namespace zin;

featureBar(li
(
    setClass('nav-item'),
    a(setClass('active'), $lang->testcase->selectStory)
));

$cols = array();
$cols['id']       = $config->story->dtable->fieldList['id'];
$cols['pri']      = $config->story->dtable->fieldList['pri'];
$cols['module']   = $config->story->dtable->fieldList['module'];
$cols['title']    = $config->story->dtable->fieldList['title'];
$cols['stage']    = $config->story->dtable->fieldList['stage'];
$cols['openedBy'] = $config->story->dtable->fieldList['openedBy'];
$cols['estimate'] = $config->story->dtable->fieldList['estimate'];
$cols['title']['data-toggle'] = 'modal';
$cols['title']['data-size']   = 'lg';

$cols['module']['map'] = $modules;
foreach($cols as $colKey => $col) $cols[$colKey]['sortType'] = false;

$footToolbarGroup = array('type' => 'class', 'component' => 'div', 'className' => 'input-group size-sm');
$footToolbarGroup['html'] = "<input type='text' class='form-control w-14' autocomplete='off' name='num' value='10' id='num'><select class='form-control w-14' name='fileType'><option value='xlsx'>xlsx</option><option value='xls'>xls</option></select><button type='submit' class='btn primary' >{$lang->testcase->exportTemplate}</button>";

form
(
    setID('selectStoryForm'),
    set::url($this->createLink('testcase', 'exportTemplate', "productID={$product->id}")),
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    set::actions(array()),
    dtable
    (
        set::userMap($users),
        set::cols($cols),
        set::data($stories),
        set::checkable(true),
        set::loadPartial(true),
        set::showToolbarOnChecked(false),
        set::footToolbar(array($footToolbarGroup)),
        set::footPager(usePager())
    )
);

pageJS(<<<JAVASCRIPT
window.clickSubmit = function(e)
{
    const checkedList = zui.DTable.query($(e.target).find('.dtable')).$.getChecks();
    if(!checkedList.length) return;

    const \$form = $(e.target);
    checkedList.forEach((id) => \$form.append('<input name="stories[]" value="' + id +'">'));
    $('.modal').modal('hide');
}
JAVASCRIPT
);

render();
