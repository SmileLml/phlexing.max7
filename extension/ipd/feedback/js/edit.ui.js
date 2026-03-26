window.changeProduct = function()
{
    const productID = $('[name=product]').val();
    const url = $.createLink('feedback', 'edit', 'id=' + feedbackID + '&productID=' + productID);
    loadPage(url);
}
