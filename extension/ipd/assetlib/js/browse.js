$(function()
{
    $('#libList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('assetlib', 'libSort', 'type=' + objectType), {'assetlib' : list}, function()
        {
            order = 'order_desc'
            history.pushState({}, 0, createLink('assetlib', browseLink, "orderBy=" + order));
        });
    });
})
