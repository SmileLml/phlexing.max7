window.renderCell = function(result, {row, col})
{
    if(col.name == 'type' && result) result[1]['attrs']['title'] = typeHintList[row.data.type];
    return result;
}

$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('relationIdList[]', id));

    if($(this).hasClass('batchDeleteBtn'))
    {
        zui.Modal.confirm({message: confirmBatchDelete, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url, data:form});});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});
