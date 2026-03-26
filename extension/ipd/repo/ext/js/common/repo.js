function showBugsBlock(bugs)
{
    if(!bugs.length) return;

    $('#reviewBugs').remove();
    $.get(createLink('repo', 'ajaxGetBugs', 'repoID=' + repoID + '&bugList=' + bugs.join(',')), function(data)
    {
        $('body').append(data);
    });
}
