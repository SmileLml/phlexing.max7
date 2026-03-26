$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('effortIDList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

/**
 * 提示并删除日志。
 * Delete effort with tips.
 *
 * @param  int    effortID
 * @access public
 * @return void
 */
window.confirmDelete = function(effortID)
{
    const effort     = efforts[effortID] != undefined ? efforts[effortID] : {'objectType': 'custom', 'objectID': 0,'consumed': 0};
    const task       = tasks[effort.objectID] != undefined ? tasks[effort.objectID] : {'consumed': 0};
    const confirmTip = effort.objectType == 'task' && task.consumed - effort.consumed == 0 ? confirmTaskDeleteTip : confirmDeleteTip;
    zui.Modal.confirm(confirmTip).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('effort', 'delete', 'effortID=' + effortID + '&confirm=yes&from=list')});
    });
}

/**
 *  渲染日志列表。
 */
window.renderCell = function(result, {col, row})
{
    if(col.name == 'objectTitle')
    {
        const projectID  = row.data.project;
        const objectType = row.data.objectType;
        if(['story', 'requirement', 'epic', 'productplan', 'release'].includes(objectType) && noProjectProjects.includes(projectID)) result[0].props['data-app'] = 'project';

        const canView = canViewList[row.data.objectType];
        if(!canView) result[0].type = 'span';
        if(vision != 'lite' && canView && row.data.objectType == 'feedback') result[0].props.href = $.createLink('feedback', 'adminView', 'feedbackID=' + row.data.objectID);
    }
    return result;
}
