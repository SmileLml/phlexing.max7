$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('feedbackIDList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data:form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

window.renderCell = function(result, info)
{
    if(info.col.name == 'title' && result)
    {
        const module = this.options.modules[info.row.data.module];
        if(module) result.unshift({html: '<span class="label gray-pale rounded-full whitespace-nowrap w-auto">' + module + '</span>'}); // 添加模块标签
    }

    if(info.col.name == 'status' && result)
    {
        result[0].props.children = info.row.data.realStatus;
    }

    return result;
};
