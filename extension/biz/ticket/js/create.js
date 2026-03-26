$(function()
{
    if(fromType == 'feedback') $("#navbar .nav li[data-id=browse]").addClass('active');
    if(notifyEmailRequired) $("input[name^=notifyEmail").parent().addClass('required');

    /* Init pri. */
    $('#pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });
});

$('#product').change(function()
{
    var productID = $(this).val();
    $('#assignedTo').val(productTicket[productID]).trigger('chosen:updated');
})

/**
 * Add item
 *
 * @param  obj   $obj
 * @access public
 * @return void
 */
function addItem(obj)
{
    var item = $('#addItem').html().replace(/%i%/g, itemIndex);
    $(obj).closest('tr').after('<tr class="addedItem">' + item  + '</tr>');
    itemIndex ++;
    $('.addedItem #mailBox').css('flex', '0 0 ' + gap + 'px')
}

/**
 * Delete item.
 *
 * @param  obj  $obj
 * @access public
 * @return void
 */
function deleteItem(obj)
{
    $(obj).closest('tr').remove();
}

function loadProductModules(productID)
{
    loadModules(productID);
}
