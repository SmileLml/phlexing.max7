$(function()
{
    $('#faq').change(function()
    {
        var faqID = $(this).val();
        link = createLink('faq', 'ajaxGetAnswer', 'faqID=' + faqID);        
        $.post(link, function(data)
        {
            editor = KindEditor.instances[0];
            editor.focus();
            editor.html(data); 
        })
    })        
})
