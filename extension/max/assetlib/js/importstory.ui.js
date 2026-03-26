$(document).off('click', '[data-formaction]').on('click', '[data-formaction]', function()
{
    const $this       = $(this);
    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('storyIdList[]', id));
    postData.append('lib', libID);

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
        if(html) result.unshift({html});
    }
    return result;
};

$(document).off('click', '.switchButton').on('click', '.switchButton', function()
{
    const storyViewType = $(this).attr('data-type');
    $.cookie.set('storyViewType', storyViewType, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
});

window.changeProject = function(e)
{
    const projectID = e.target.value;
    const link      = $.createLink('assetlib', 'importstory', `libID=${libID}&projectID=${projectID}`);

    loadPage(link);
}

window.changeProduct = function(e)
{
    const projectID = $('[name="fromProject"]').val();
    const productID = e.target.value;
    const link      = $.createLink('assetlib', 'importstory', `libID=${libID}&projectID=${projectID}&productID=${productID}`);

    loadPage(link);
}
