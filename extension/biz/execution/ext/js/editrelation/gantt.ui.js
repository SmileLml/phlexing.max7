window.loadTasks = function(event)
{
    const $pretask = $(event.target).closest('.form').find('[name^=pretask]').zui('picker');
    const $task    = $(event.target).closest('.form').find('[name^=task]').zui('picker');
    const $type    = $(event.target).closest('.form-group').attr('data-name');
    const $picker  = $type == 'pretask' ? $pretask : $task;

    const linkType  = $type == 'pretask' ? 'task' : 'pretask';
    const linkValue = $type == 'pretask' ? $task.$.value : $pretask.$.value;

    const getTaskLink = $.createLink('execution', 'ajaxGetRelationTasks', 'projectID=' + projectID + '&executionID=' + executionID + '&taskID=' + linkValue + '&taskType=' + linkType + '&appendTask=' + $picker.$.value);

    // 使用$.post方法提交表单数据
    let formData = getFormData();
    formData['selectedValue'] = $picker.$.value;
    $.post(getTaskLink, formData, function(relations) {
        let data = JSON.parse(relations);
        if(data.result == 'success')
        {
            // 处理服务器响应
            $picker.render({items: data.message});
        }
        else
        {
            zui.Modal.alert({message: data.message, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'});
            $(event.target).zui('picker').$.setValue('');
        }
    }, 'json');
}

window.changeTask = function(e)
{
    $('.title-suffix .icon').remove();
}
