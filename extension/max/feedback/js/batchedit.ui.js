$(function()
{
    $('#mainNavbar .nav .nav-item').find("[href$='feedback']").addClass('active');
});

window.renderRowData = function($row, index, row)
{
    if(modules[row.product] != undefined)
    {
        $row.find('[data-name="module"]').find('.picker-box').on('inited', function(e, info)
        {
            let $module = info[0];
            let items   = modules[row.product];
            $module.render({items});
        });
    }
}

window.changeProduct = function(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const product     = $target.val();

    let items = modules[product];
    $currentRow.find('[name^=module]').zui('picker').render({items});
    $currentRow.find('[name^=module]').zui('picker').$.changeState({value: 0});
}
