$(document).off('click', '.importlib-btn').on('click', '.importlib-btn', function()
{
    const $this       = $(this);
    const dtable      = zui.DTable.query($('#storyList'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('storyIdList[]', id));

    if($this.data('page') == 'batch')
    {
        postAndLoadPage($this.data('formaction'), postData);
    }
    else
    {
        $.ajaxSubmit({"url": $this.data('formaction'), "data": postData});
    }
});

window.renderCell = function(result, info)
{
    if(info.col.name == 'title' && result)
    {
        const story = info.row.data;
        let html = '';

        let gradeLabel = '';
        if(showGrade || story.grade >= 2) gradeLabel = gradeGroup[story.type][story.grade]?.name;
        if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
        if(story.color) result[0].props.style = 'color: ' + story.color;
        if(html) result.unshift({html});
    }
    return result;
};

window.changeLib = function(e)
{
    const libID = $(e.target).val();
    const link  = $.createLink('projectstory', 'importFromLib', 'projectID=' + projectID + '&productID=' + productID + '&libID=' + libID + '&storyType=' + storyType);

    loadPage(link);
}
