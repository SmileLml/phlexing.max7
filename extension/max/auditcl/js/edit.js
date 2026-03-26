/**
 * change process
 *
 * @param  processID $processID
 * @access public
 * @return void
 */
function changeProcess(processID)
{
    objectID = objectType == 'activity' ? objectID : 0;
    link = createLink('auditcl', 'ajaxGetAll', 'objectType=activity' + '&processID=' + processID + '&activityID=0&objectID=' + objectID);
    $.post(link, function(data)
    {
        $('#activity').replaceWith(data); 
        $('#activity_chosen').remove();
        $('#activity').chosen()
    })

    changeActivity(0);
}

/**
 * change activity
 *
 * @param  activityID $activityID
 * @access public
 * @return void
 */
function changeActivity(activityID)
{
    processID = $('#process').val();
    objectID  = objectType == 'zoutput' ? objectID : 0;

    link = createLink('auditcl', 'ajaxGetAll', 'objectType=zoutput' + '&processID=' + processID + '&activityID=' + activityID + '&objectID=' + objectID);
    $.post(link, function(data)
    {
        $('#zoutput').replaceWith(data); 
        $('#zoutput_chosen').remove();
        $('#zoutput').chosen()
    })
}
