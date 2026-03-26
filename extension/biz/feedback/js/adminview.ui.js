window.getFeedbackID = function($element)
{
    getProjects($element);
    getExecutions(0);
}

function getProjects(obj)
{
    var productID = $(obj).attr("data-product");
    var link      = $.createLink('feedback', 'ajaxGetProjects', 'productID=' + productID + '&field=taskProjects&onchange=&getHTML=0');

    $.getJSON(link, function(data)
    {
        $picker = $('[name=taskProjects]').zui('picker');
        $picker.render({items: data.items});
    })
}

function getExecutions(projectID)
{
    if(projectID)
    {
        var langLink = $.createLink('feedback', 'ajaxGetExecutionLang', 'projectID=' + projectID);
        $.post(langLink, function(executionLang)
        {
            $('.executionHead span').html(executionLang);
        })
    }

    var link = $.createLink('feedback', 'ajaxGetExecutions', 'projectID=' + projectID + '&getHTML=0');
    $.getJSON(link, function(data)
    {
        $picker = $('[name=executions]').zui('picker');
        $picker.render({items: data.items});
    })
}

window.createTask = function()
{
    var projectID   = $('[name=taskProjects]').val();
    var executionID = $('[name=executions]').val();
    var executionID = executionID ? parseInt(executionID) : 0;

    if(projectID && executionID)
    {
        $('#toTask').zui('modal').hide();
        return loadPage($.createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=' + moduleID + '&taskID=0&todoID=0&extra=projectID=' + projectID + ',feedbackID=' + feedbackID));
    }
    if(!projectID) return zui.Modal.alert(langNoProject);
    return zui.Modal.alert(langNoExecution);
};
