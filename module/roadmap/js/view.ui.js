window.renderStoryCell = function(result, info)
{
    const story = info.row.data;
    if(info.col.name == 'title' && result)
    {
        let html = '';
        let gradeLabel = gradeGroup[story.type][story.grade];
        if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
        if(html) result.unshift({html});
    }

    if(info.col.name == 'sort')
    {
        result[0] = {html: "<i class='icon-move'></i>", className: 'text-gray cursor-move move-roadmap'};
    }
    return result;
};

window.beforeCheckRows = function(checkedIds, checkedStatus)
{
    const dtable        = zui.DTable.query($('#unlinkStoryList'));
    let   checkChildIds = {};
    for(let key in checkedIds)
    {
        let checkedID = checkedIds[key];
        if(checkedStatus[checkedID] == false) continue;

        rowData = dtable.$.getRowInfo(checkedID);
        if(typeof(rowData.data.children) != 'undefined')
        {
            children = rowData.data.children;
            for(let i in children) checkChildIds[children[i]] = true;
        }
    }
    return checkChildIds;
}

window.onSortEnd = function(from, to, type)
{
    if(!from || !to) return false;

    const url  = $.createLink('roadmap', 'ajaxStorySort', `roadmapID=${roadmapID}`);
    const form = new FormData();

    form.append('stories',    JSON.stringify(this.state.rowOrders));
    form.append('orderBy',    orderBy);
    form.append('pageID',     storyPageID);
    form.append('recPerPage', storyRecPerPage);
    form.append('recTotal',   storyRecTotal);

    $.ajaxSubmit({url, data:form});
    $.apps.updateAppUrl($.createLink('roadmap', 'view', `roadmap=${roadmapID}&type=story&orderBy=order_desc`));
    return true;
}

window.setStatistics = function(element, checkedIdList, pageSummary)
{
    if(checkedIdList == undefined || checkedIdList.length == 0) return {html: pageSummary};

    let estimate = 0;
    let total    = 0;

    const rows = element.layout.allRows;
    rows.forEach((row) => {
        if(checkedIdList.includes(row.id))
        {
            const story = element.getRowInfo(row.id);
            total += 1;
            if(story.data.isParent == '0') estimate += parseFloat(story.data.estimate);
        }
    });

    return {html: checkedSummary.replace('%total%', total).replace('%estimate%', estimate.toFixed(1))};
}

window.showLink = function(params, onlyUpdateTable)
{
    const url = $.createLink('roadmap', 'linkUR', 'roadmapID=' + roadmapID + (params || '&browseType=&param='));
    if(onlyUpdateTable)
    {
        loadComponent($('#stories').find('.dtable').attr('id'), {url: url, component: 'dtable', partial: true});
        return;
    }
    loadTarget({url: url, target: 'stories'});
};

window.onSearchLinks = function(result)
{
    const params = $.parseLink(result.load).vars[4];
    showLink(params ? atob(params[1]) : null, true);
    return false;
};

$(function()
{
    if(link == 'true') showLink(null);
});

$(document).off('click', '.batch-btn > a, .batch-btn').on('click', '.batch-btn > a, .batch-btn', function()
{
    const $this = $(this);
    if($this.data('disabled')) return;

    const type        = $this.data('type');
    const dtable      = zui.DTable.query($('#' + type + 'DTable'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    const url      = $this.data('url');
    checkedList.forEach((id) => postData.append(type + 'IdList[]', id));
    if($this.data('account')) postData.append('assignedTo', $this.data('account'));

    if($this.data('page') == 'batch')
    {
        postAndLoadPage(url, postData);
    }
    else
    {
        $.ajaxSubmit({url: url, data: postData});
    }
});

window.handleLinkObjectClick = function($this)
{
    const dtable = zui.DTable.query($this);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('stories[]', id));

    $.ajaxSubmit({url: $this.data('url'), data: postData});
};
