$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('opportunityIDList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data: form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
}).off('click', '#actionBar .export-btn').on('click', '#actionBar export-btn', function()
{
    const dtable = zui.DTable.query($('#table-opportunity-browse'));
    const checkedList = dtable ? dtable.$.getChecks() : [];
    if(!checkedList.length) return;

    $.cookie.set('checkedItem', checkedList, {expires:config.cookieLife, path:config.webRoot});
});

window.getCheckedOpportunityIDList = function()
{
    var opportunityIDList = '';

    const dtable = zui.DTable.query('#table-opportunity-browse');
    $.each(dtable.$.getChecks(), function(index, opportunityID)
    {
        if(index > 0) opportunityIDList += ',';
        opportunityIDList += opportunityID;
    });

    $('[name=opportunityIDList]').val(opportunityIDList);
}
