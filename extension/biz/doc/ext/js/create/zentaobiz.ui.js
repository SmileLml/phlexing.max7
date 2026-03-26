window.openEditURL = function(docID, fileID)
{
    const editUrl = $.createLink('file', 'download', "fileID=" + fileID + "&mouse=left");
    window.open(editUrl);
    loadPage($.createLink('doc', 'view', "docID=" + docID));
}
