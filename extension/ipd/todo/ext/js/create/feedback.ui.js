window.waitDom("[name='type']", function()
{
    if(feedback)
    {
        loadList('feedback', 0, 'feedback', feedback.id);
        $("[name='name']").val(feedback.title);
    }
})
