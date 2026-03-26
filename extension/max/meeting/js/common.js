$(function()
{
    $('#objectType').change(function()
    {
        var objectType = $(this).val();
        var link       = createLink('meeting', 'ajaxGetObjects' , 'projectID=' + projectID + '&objectType=' + objectType);
        $('#objectBox').load(link, function()
        {
            $('#objectID').chosen();
        });
    })
})

/**
 * Load executions of project.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */

function loadProjectExecutions(projectID = 0)
{
    var link = createLink('execution', 'ajaxGetProjectExecutions', "projectID=" + projectID + "&multiple=1");
    $.post(link, function(data)
    {
        $('#execution').replaceWith(data);
        $('#execution_chosen').remove();
        $('#execution').next('.picker').remove();
        $('#execution').chosen();
    })
    if(typeof noSprintPairs != 'undefined') $('#execution').closest('tr').toggleClass('hidden', noSprintPairs[projectID] != undefined);

    loadTeamMembers(projectID)
}

/**
 * Load team.
 *
 * @param  int    $objectID
 * @access public
 * @return void
 */

function loadTeamMembers(objectID = 0)
{
    var projectID = $("#project").val();

    if(objectID == 0 && projectID) objectID = projectID;

    var participant = $('#participant').val();
    var link        = createLink('meeting', 'ajaxGetTeamMembers', "objectID=" + objectID + '&selected=' + participant);

    $.post(link, function(data)
    {
        $('#contactListGroup .picker-multi').remove();
        $('#participant').replaceWith(data);
        $('#participant').picker();
    })
}
