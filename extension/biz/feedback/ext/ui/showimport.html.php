<?php
namespace zin;
include $app->getModuleRoot() . 'transfer/ui/showimport.html.php';

jsVar('modulesProductMap', $modulesProductMap);
jsVar('datas', array_values($datas));

pageJS(<<<JAVASCRIPT
window.changeModule = function(e)
{
    const productID      = $(e.target).val();
    var link = $.createLink('feedback', 'ajaxGetModule', 'productID=' + productID + '&isChosen=false' + '&number=0&moduleID=0&ajaxGetModule=1');
    $.getJSON(link, function(data)
    {
        const \$modulePicker = $(e.target).closest('tr').find('.form-batch-control[data-name=module] .picker').zui('picker');
        const oldModuleID    = $(e.target).closest('tr').find('.form-batch-control[data-name=module] .picker input').val();
        \$modulePicker.render({items: data});
        \$modulePicker.$.setValue(oldModuleID);
    });
}

$(function()
{
    var tbodyInited = false;
    $('#mainContainer > #mainContent .panel-body .form .form-batch-container .table tbody').on('inited', function()
    {
        if(tbodyInited) return false;
        tbodyInited = true;
        $('.form-batch-container .form-batch-control[data-name=module]').find('.picker-box').on('inited', function(e)
        {
            const index          = $(e.target).closest('tr').attr('data-index');
            const productID      = datas[index].product;
            const \$modulePicker = $(e.target).find('.picker').zui('picker');
            \$modulePicker.render({items: modulesProductMap[productID]});

            $(e.target).closest('tr').find('.form-batch-control[data-name=product] .picker').attr('data-on', 'change').attr('data-do', 'changeModule(event)');;
        });
    });
});
JAVASCRIPT
);
