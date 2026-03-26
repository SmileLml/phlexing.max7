function reload(libID)
{
    link = createLink('issue', 'importFromLib', 'projectID=' + projectID + '&from=' + from + '&libID=' + libID);
    location.href = link;
}
