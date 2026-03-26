$(function()
{
    $('#navbar ul.nav li[data-id="'+ browseType +'"]').addClass('active');
    if(noChangeIDList != '') bootbox.alert(batchEditTip);
})

/**
 * Change module by product id
 *
 * @param int productID
 * @param int index
 */
function changeModule(productID, index)
{
    var link = createLink('feedback', 'ajaxGetModule','productID=' + productID + '&isChosen=true&number=' + index);
    $.post(link, function(data)
    {
        $('#module' + index).replaceWith(data);
        $('#module' + index + '_chosen').remove();
        $('#module' + index).chosen();
        $('#module' + index).change();
    })
}
