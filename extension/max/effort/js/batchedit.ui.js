window.renderRowData = function($row, index, row)
{
    const disabled = ['risk', 'issue', 'opportunity'].includes(row.objectType);

    /* Set disabled to product. */
    if(row.product != undefined)
    {
        $row.find('[data-name="product"]').find('.picker-box').on('inited', function(e, info)
        {
            info[0].render({disabled: disabled || shadowProducts[row.product] != undefined});
        });
    }

    /* Set disabled to execution. */
    if(row.execution != undefined)
    {
        $row.find('[data-name="execution"]').find('.picker-box').on('inited', function(e, info)
        {
            info[0].render({disabled: disabled});
        });
    }

    /* Set disabled to left. */
    if(row.left != undefined)
    {
        const $left = $row.find('[data-name="left"] input');
        const attr  = row.objectType != 'task' ? 'disabled' : 'readonly';
        $left.attr(attr, true);
        $left.addClass(attr);
    }
}
