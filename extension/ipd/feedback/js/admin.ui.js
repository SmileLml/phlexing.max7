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

window.clickTotask = function(event)
{
    const params = $(event.target).closest('a').attr('href').split('&');
    $('#feedbackID').val(params[0]);
    getProjects(params[1]);
};

window.toTask = function()
{
    const projectID   = $('[name="taskProjects"]').val();
    const executionID = $('[name="executions"]').val() ? $('[name="executions"]').val() : 0;
    const feedbackID  = $('#feedbackID').val();

    if(projectID && executionID != 0)
    {
        zui.Modal.hide('#toTask');

        const url = $.createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=0&taskID=0&todoID=0&extra=projectID=' + projectID + ',feedbackID=' + feedbackID);
        loadPage(url);
    }
    else if(projectID == 0)
    {
        zui.Modal.alert(errorNoProject);
    }
    else
    {
        zui.Modal.alert(errorNoExecution);
    }
};

function getProjects(productID)
{
    const link = $.createLink('feedback', 'ajaxGetProjects', 'productID=' + productID + '&field=taskProjects');
    $.getJSON(link, function(data)
    {
        if(data)
        {
            let $projectPicker = $('[name=taskProjects]').zui('picker');
            $projectPicker.render(data);
            $projectPicker.$.setValue('');
        }
    });
}

function changeTaskProjects(event)
{
    const projectID = event != undefined ?  $(event.target).val() : $('[name="taskProjects"]').val();
    if(!projectID) return;

    const link = $.createLink('feedback', 'ajaxGetExecutions', 'projectID=' + projectID);
    $.getJSON(link, function(data)
    {
        if(data)
        {
            let $executionPicker = $('[name=executions]').zui('picker');
            $executionPicker.render(data);
            $executionPicker.$.setValue(data.defaultValue);
        }
    });
}

window.firstRendered = false;
window.toggleCheckRows = function(idList)
{
    if(!idList?.length || firstRendered) return;
    firstRendered = true;
    const dtable = zui.DTable.query($('#feedbacks'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}

window.checkedChange = function(changes)
{
    if(!this._checkedRows) this._checkedRows = {};
    Object.keys(changes).forEach((rowID) =>
    {
        const row = this.getRowInfo(rowID);
        if(row !== undefined) this._checkedRows[rowID] = row.data;
    });
}

window.insertListToDoc = function()
{
    const dtable      = zui.DTable.query($('#feedbacks'));
    const myTable     = dtable.$;
    const checkedList = Object.keys(myTable.state.checkedRows);
    if(!checkedList.length) return;

    let {cols} = dtable.options;
    const data = checkedList.map(rowID => myTable._checkedRows[rowID]).filter(item => item != undefined);
    const docID = getDocApp()?.docID;

    const url = $.createLink('doc', 'buildZentaoList', `docID=${docID}&type=feedback&blockID=${blockID}`);
    const formData = new FormData();
    formData.append('cols', JSON.stringify(cols));
    formData.append('data', JSON.stringify(data));
    formData.append('idList', checkedList.join(','));
    formData.append('url', insertListLink);
    $.post(url, formData, function(resp)
    {
        resp = JSON.parse(resp);
        if(resp.result == 'success')
        {
            const oldBlockID = resp.oldBlockID;
            const newBlockID = resp.newBlockID;
            zui.Modal.hide();
            window.insertZentaoList && window.insertZentaoList('feedback', newBlockID, null, oldBlockID) ;
        }
    });
}

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
