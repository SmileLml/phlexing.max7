$(function()
{
    $('#libList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('caselib', 'libSort'), {'caselib' : list}, function()
        {
            order = 'order_desc'
            history.pushState({}, 0, createLink('assetlib', 'caselib', "type=all&orderBy=" + order));
        });
    });
})
