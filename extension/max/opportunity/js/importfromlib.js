function reload(libID)
{
    link = createLink('opportunity', 'importFromLib', 'projectID=' + projectID + '&from=' + from + '&libID=' + libID);
    location.href = link;
}
