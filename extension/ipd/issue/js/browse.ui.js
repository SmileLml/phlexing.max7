window.getCheckedIssueIdList = function()
{
    let issueIDList = '';

    const dtable = zui.DTable.query('#table-issue-browse');
    $.each(dtable.$.getChecks(), function(index, issueID)
    {
        if(index > 0) issueIDList += ',';
        issueIDList += issueID;
    });
    $('#batchImportToLib [name=issueIDList]').val(issueIDList);
}
