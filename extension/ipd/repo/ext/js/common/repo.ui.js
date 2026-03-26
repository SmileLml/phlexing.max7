function showBugsBlock(bugs)
{
    if(!bugs.length) return;

    $('#reviewBugContainer').show();
    $('#monacoEditor').css('width', '75%');
    $('.view-bugs').css('left', getViewBugIconLeft() + 'px');
    const link = $.createLink('repo', 'ajaxGetBugs', 'repoID=' + repoID + '&bugList=' + bugs.join(','));
    $('#reviewBugContainer').html("<iframe class='bug-iframe' src='" + link + "' width='100%' height='100%'></iframe>");
}

window.removeReviewBug = function()
{
    $('#reviewBugContainer').hide();
    $('#reviewBugContainer').html('');
    $('#monacoEditor').css('width', '100%');
    setTimeout(() => {
        $('.view-bugs').css('left', getViewBugIconLeft() + 'px');
    }, 100);
};

function getViewBugIconLeft()
{
    var monacoWidth       = $('#codeContainer').width();
    var minimapWidth      = $('.minimap-decorations-layer').width();
    var overlaysWidth     = $('.margin-view-overlays').width();
    var bugContainerWitdh = $('#reviewBugContainer').width()
    var oldBugLeft        = parseInt($('.view-bugs').css('left'));
    var viewBugsLeft      =  monacoWidth - minimapWidth - overlaysWidth - 65;
    if(bugContainerWitdh > 50 && oldBugLeft - viewBugsLeft < 50) return oldBugLeft;

    if(typeof pageType !== 'undefined' && pageType === 'diff') viewBugsLeft = viewBugsLeft - 70;
    return viewBugsLeft;
}

window.addEventListener('resize', function()
{
    setTimeout(() => {
        $('.view-bugs').css('left', getViewBugIconLeft() + 'px');
    }, 100);
});
