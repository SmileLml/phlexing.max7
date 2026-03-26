window.changeCategory = function()
{
    const category = $("[name='category']").val();
    if(model == 'ipd') toggleHasProduct(category);
}

window.toggleHasProduct = function(category)
{
    const charterID = $('[name=charter]').val();
    if(category == 'CPD' && charterID <= 0)
    {
        $('.categoryBox .hasProduct').removeClass('hidden');
    }
    else
    {
        $('.categoryBox .hasProduct').find("input[name='hasProduct'][value='1']").prop('checked', true);
        $('.categoryBox .hasProduct').addClass('hidden');
    }
}
