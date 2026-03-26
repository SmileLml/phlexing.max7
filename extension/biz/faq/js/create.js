$(function()
{
    var productID = $('#product').val();
    loadProductModules(productID);
})

function loadProductModules(productID)
{
    link = createLink('faq', 'ajaxGetModules', 'productID=' + productID);
    $.post(link, function(data)
    {
        $('#module').replaceWith(data); 
        $('#module_chosen').remove(); 
        if(moduleID != 0) $('#module').val(moduleID);
        $('#module').chosen(); 
    })
}
