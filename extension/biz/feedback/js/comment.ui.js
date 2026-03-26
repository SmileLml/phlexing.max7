window.faqChange = function()
{
    const faqID = $('[name=faq]').val();
    const link  = $.createLink('faq', 'ajaxGetAnswer', 'faqID=' + faqID);
    $.post(link, function(data)
    {
        $('zen-editor[name=comment]')[0].setHTML(data);
    })
}
