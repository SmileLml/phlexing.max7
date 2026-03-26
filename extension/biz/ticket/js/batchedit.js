/**
 * Change module by product id
 *
 * @param int productID
 * @param int index
 */
 function changeModule(productID, index)
 {
     var link = createLink('ticket', 'ajaxGetModule','productID=' + productID + '&isChosen=1&number=' + index);
     $.post(link, function(data)
     {
         $('#modules' + index).replaceWith(data);
         $('#modules' + index + '_chosen').remove();
         $('#modules' + index).chosen();
         $('#modules' + index).change();
     })
 }
