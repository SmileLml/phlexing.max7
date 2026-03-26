window.updateCurrentPicker = function(event, taskID, taskType)
{
    const $picker     = event.zui('picker');
    const getTaskLink = $.createLink('execution', 'ajaxGetRelationTasks', 'projectID' + projectID + '&executionID=' + executionID + '&taskID=' + taskID + '&taskType=' + taskType);

    $.getJSON(getTaskLink, function(relations) {$picker.render({items: relations.message});});
}

window.renderRowData = function($row, index, relation)
{
    $row.attr('data-id', relation.id);

    let $preTask  = $row.find('.form-batch-control[data-name="pretask"]');
    let $postTask = $row.find('.form-batch-control[data-name="task"]');

    if($preTask.length > 0)
    {
        $preTask.find('.picker-box').on('inited', function(e, info)
        {
            updateCurrentPicker($(e.target).find("[name^=pretask]"), relation.task, 'task');
        })
    }

    if($postTask.length > 0)
    {
        $postTask.find('.picker-box').on('inited', function(e, info)
        {
            updateCurrentPicker($(e.target).find("[name^=task]"), relation.pretask, 'pretask');
        })
    }

    if(relationErrors[relation.id])
    {
        let errorTips = '';
        $.each(relationErrors[relation.id], function(index, error)
        {
            errorTips += error + '\n';
        });
        $row.find("div[data-name='index']").prepend("<i class='icon icon-exclamation-sign mr-1' data-toggle='tooltip' data-title='" + errorTips + "' data-placement='top-end'></i>");
    }
}

window.changeTask = function(e)
{
    $(e.target).closest('tr').find('div[data-name="id"] .icon').remove();
}
