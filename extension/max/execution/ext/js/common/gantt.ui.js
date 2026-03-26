window.loadTasks = function(event)
{
    const $pretask = $(event.target).closest('tr').find('[name^=pretask]').zui('picker');
    const $task    = $(event.target).closest('tr').find('[name^=task]').zui('picker');
    const $type    = $(event.target).closest('td').attr('data-name');
    const $picker  = $type == 'pretask' ? $pretask : $task;
    const $value   = $type == 'pretask' ? $task.$.value : $pretask.$.value;

    let formData = getFormData();
    formData['selectedValue'] = $picker.$.value;

    const $linkType = $type == 'pretask' ? 'task' : 'pretask';

    const getTaskLink = $.createLink('execution', 'ajaxGetRelationTasks', 'projectID=' + projectID + '&executionID=' + executionID + '&taskID=' + $value + '&taskType=' + $linkType + '&appendTask=' + $picker.$.value);

    // 使用$.post方法提交表单数据
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

window.getFormData = function()
{
    const form = document.querySelector('form');

    // 创建一个空对象来存储表单数据
    const formData = {};

    // 遍历表单中的所有输入字段并将它们存储在对象中
    for (let element of form.elements) {
        if (element.name) {
            formData[element.name] = element.value;
        }
    }

    // 返回表单数据
    return formData;
}
