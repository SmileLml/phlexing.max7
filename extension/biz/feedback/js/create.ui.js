window.changeProduct = function()
{
    const productID = $('[name=product]').val();
    const url       = $.createLink('feedback', 'create', 'extras=productID=' + productID);
    loadPage({url: url, selector: '#moduleBox'});
}

$(function()
{
    if(productID == 'all') $('#moduleBox span.input-group-btn').addClass('hidden');
})
