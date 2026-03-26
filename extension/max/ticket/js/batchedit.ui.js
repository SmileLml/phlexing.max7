$(function()
{
    if(batchEditTip != '') zui.Modal.alert(batchEditTip);
});

window.renderRowData = function($row, index, row)
{
    /* Show the modules of current ticket's product. */
    if(modules[row.product] != undefined && modules[row.product] != undefined)
    {
        $row.find('[data-name="module"]').find('.picker-box').on('inited', function(e, info)
        {
            let bugModules = modules[row.product];
            let $module    = info[0];
            $module.render({items: bugModules});
        });
    }
}

/**
 * Change module by product id
 *
 * @param object event
 */
 function changeModule(event)
 {
     const $target       = $(event.target);
     const $currentRow   = $target.closest('tr');
     const productID     = $target.val();
     const $modulePicker =  $currentRow.find('[name^="module"]').zui('picker');
     $modulePicker.render({items: modules[productID]});
 }
