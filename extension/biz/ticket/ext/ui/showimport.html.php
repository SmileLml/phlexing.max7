<?php
namespace zin;

include $app->getModuleRoot() . 'transfer/ui/showimport.html.php';

jsVar('modulesProductMap', $modulesProductMap);
jsVar('buildsProductMap', $buildsProductMap);
jsVar('datas', array_values($datas));
jsVar('fields', $fields);

pageJS(<<<JAVASCRIPT
$(function()
{
    var tbodyInited = false;
    $('#mainContainer > #mainContent .panel-body .form .form-batch-container .table tbody').on('inited', function()
    {
        if(tbodyInited) return false;
        tbodyInited = true;
        $('.form-batch-container .form-batch-control[data-name=product]').last().find('.picker-box').on('inited', function(e)
        {
            $('.form-batch-container .form-batch-control[data-name=product]').find('.picker').attr('data-on', 'change');
            $('.form-batch-container .form-batch-control[data-name=product]').find('.picker').attr('data-do', 'changeModule(event)');
        });
        $('.form-batch-container .form-batch-control[data-name=module]').find('.picker-box').on('inited', function(e)
        {
            const index          = $(this).closest('tr').attr('data-index');
            const productID      = datas[index].product;
            const \$modulePicker = $(this).find('.picker').zui('picker');
            \$modulePicker.render({items: modulesProductMap[productID]});
        });
        $('.form-batch-container .form-batch-control[data-name=openedBuild]').find('.picker-box').on('inited', function(e)
        {
            const index         = $(this).closest('tr').attr('data-index');
            const productID     = datas[index].product;
            const \$buildPicker = $(this).find('.picker').zui('picker');
            \$buildPicker.render({items: buildsProductMap[productID]});
        });

    });
});
window.changeModule = function(e)
{
    const \$this         = $(e.target);
    const \$tr           = \$this.closest('tr');
    const productID      = \$this.val();
    const index          = \$tr.attr('data-index');
    const \$modulePicker = \$tr.find('.form-batch-control[data-name=module] .picker').zui('picker');
    const oldModule      = \$tr.find('.form-batch-control[data-name=module] .picker input').val();
    \$modulePicker.render({items: modulesProductMap[productID]});
    \$modulePicker.$.setValue(oldModule);

    const \$buildPicker = \$tr.find('.form-batch-control[data-name=openedBuild] .picker').zui('picker');
    const oldBuild      = \$tr.find('.form-batch-control[data-name=openedBuild] .picker input').val();
    \$buildPicker.render({items: buildsProductMap[productID]});
    \$buildPicker.$.setValue(oldBuild);
}
JAVASCRIPT
);
?>
