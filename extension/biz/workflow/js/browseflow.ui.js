$(document).off('click', '.switchButton').on('click', '.switchButton', function()
{
    const viewType = $(this).attr('data-type');
    $.cookie.set('flowViewType', viewType, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
});

onRenderCell = function(result, {row, col})
{
    if(result)
    {
        if(col.name == 'buildin') return [{html: row.data.buildin == 1 ? '<i class=\"icon icon-check text-success\"></i>' : '<i class=\"icon icon-close text-danger\"></i>'}];
        if(col.name == 'actions')
        {
            for(i in result[0].props.items)
            {
                let item = result[0].props.items[i];
                if(item.disabled) continue;
                if(row.data.belong == '' && item.icon == 'off') delete item['data-confirm'];
                if(row.data.belong == '' && item.icon == 'play') item['url'] = $.createLink('workflow', 'activate', `id=${row.data.id}&type=all`);
            }
        }
    }
    return result;
}

window.activate = function(id)
{
    zui.Modal.confirm(
        {
            'message' : activateTips,
            'actions': [
                {text: activateList['all'], key: 'confirm', class: 'primary'},
                {text: activateList['single'], key: 'cancel'},
            ],
            onResult: function(result)
            {
                result = result ? 'all' : 'single';
                const link = $.createLink('workflow', 'activate', `id=${id}&type=${result}`);
                $.ajaxSubmit({url: link});
            }
        }
    );
}
