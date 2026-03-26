/**
 * Delete product row
 */
function deleteProduct(clickedButton)
{
    if($('#objectTable tbody tr').length == 1)
    {
        $('.productSetBox option').removeAttr('selected')
        $(clickedButton).parent().parent().find('select').trigger('chosen:updated');

        return false;
    }
    $(clickedButton).parent().parent().remove();
}

/**
 * Add product row
 */
function addProduct(clickedButton)
{
    productRow = '<tr class="productSetBox">' + $(clickedButton).closest('tr').html() + '</tr>';
    var newStr = productRow.replace(/selected="selected"/g, "");
    $(clickedButton).closest('tr').after(newStr);
    var nextBox = $(clickedButton).closest('tr').next('.productSetBox');
    $(nextBox).find('.picker').remove();
    $(nextBox).find('select').picker({chosenMode: true, dropDirection: 'bottom'});
}

/**
 * change product link feedback and ticket.
 */
function changeProduct(clickedRow)
{
    var currentFeedback  = $(clickedRow).closest('tr').find('#feedbacks');
    var currentTicket    = $(clickedRow).closest('tr').find('#tickets');
    var productID        = clickedRow.value;

    var productFeedback  = productHeadMap[productID]['feedback'] ? productHeadMap[productID]['feedback'] : '';
    var productTicket    = productHeadMap[productID]['ticket'] ? productHeadMap[productID]['ticket'] : '';

    currentFeedback.val(productFeedback)
    currentFeedback.trigger('chosen:updated');

    currentTicket.val(productTicket)
    currentTicket.trigger('chosen:updated');
}
