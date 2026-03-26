function switchShow(result)
{
    $('#priBox').hide();
    if(result == 'reject')
    {
        $('#rejectedReasonBox').show();
        $('#preVersionBox').hide();
        $('#assignedToBox').hide();
    }
    else if(result == 'revert')
    {
        $('#preVersionBox').show();
        $('#rejectedReasonBox').hide();
        $('#duplicateDemandBox').hide();
        $('#childStoriesBox').hide();
        if(isLastOne) $('#assignedToBox').show();
    }
    else if(result == 'clarify')
    {
        $('#preVersionBox').hide();
        $('#rejectedReasonBox').hide();
        $('#duplicateDemandBox').hide();
        $('#childStoriesBox').hide();
        $('#rejectedReasonBox').hide();
        if(isLastOne) $('#assignedToBox').show();
    }
    else
    {
        $('#preVersionBox').hide();
        $('#rejectedReasonBox').hide();
        $('#duplicateDemandBox').hide();
        $('#childStoriesBox').hide();
        $('#rejectedReasonBox').hide();
        if(isLastOne) $('#assignedToBox').show();
        if(result == 'pass')
        {
            $('#priBox').show();
        }
    }
}

function setDemand(reason)
{
    if(reason == 'duplicate')
    {
        $('#duplicateDemandBox').show();
        $('#childStoriesBox').hide();
    }
    else if(reason == 'subdivided')
    {
        $('#duplicateDemandBox').hide();
        $('#childStoriesBox').show();
    }
    else
    {
        $('#duplicateDemandBox').hide();
        $('#childStoriesBox').hide();
    }
}
