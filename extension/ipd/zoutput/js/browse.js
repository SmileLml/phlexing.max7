$(function()
{
    $('#outputList').on('sort.sortable', function(e, data)
    {
        var outputs = '';
        for(i = 0; i < data.list.length; i++)
        {
            outputs += $(data.list[i].item).attr('data-id') + ',';
        }
        $.post(createLink('zoutput', 'updateOrder'), {'outputs' : outputs, 'orderBy' : orderBy});
    });
});
