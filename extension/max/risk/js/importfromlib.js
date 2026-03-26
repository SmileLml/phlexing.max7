function reload(libID)
{
    link = createLink('risk', 'importFromLib', 'projectID=' + projectID + '&from='+ from + '&libID=' + libID);
    location.href = link;
}
