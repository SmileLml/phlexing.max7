<?php
namespace zin;

global $app, $lang;

$productID = data('productID');

if(common::hasPriv('testcase', 'submit') && !empty($productID))
{
    if(!(($app->tab == 'project' && in_array(data('projectType'), array('scrum', 'agileplus', 'ipd'))) || $app->tab == 'qa'))
    {
        $submitReviewHtml = a
        (
            setClass('btn secondary'),
            set::href(createLink('testcase', 'submit', "productID=$productID")),
            icon('plus'),
            setData(array('type' => 'iframe', 'toggle' => 'modal')),
            on::click()->do("const dtable = zui.DTable.query($('#mainContent .dtable')); if($('#mainContent .dtable').length) {const checkedList = dtable.$.getChecks(); $.cookie.set('checkedItem', checkedList, {expires:config.cookieLife, path:config.webRoot});}"),
            $lang->testcase->submit
        );

        query('#actionBar .btn-group')->before($submitReviewHtml);
    }
}
