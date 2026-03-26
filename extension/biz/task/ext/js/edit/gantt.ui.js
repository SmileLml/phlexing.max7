window.clickSubmit = async function(e)
{
    const $taskForm = $('[formid=taskEditForm' + taskID + ']');
    if(confirmSyncTip.length > 0 && $('[name=story]').length > 0 &&  $('[name=story]').val() != '' && $('[name=story]').val() != '0' && $('[name=story]').val() != taskStory)
    {
        await zui.Modal.confirm(confirmSyncTip).then((res) =>
        {
            $taskForm.find('[name=syncChildren]').remove();
            $taskForm.append('<input type="hidden" name="syncChildren" value="' + (res ? '1' : '0') + '" />');
        });
    }

    return checkRelation();
}

async function checkRelation()
{
    let confirmParentRelation = false;
    if($('[name=parent]').length && $('[name=parent]').val() && $('[name=parent]').val() != task.parent)
    {
        await $.get($.createLink('task', 'ajaxGetRelation', 'taskIdList=' + $('[name=parent]').val()), function(data)
        {
            if(data) confirmParentRelation = true;
        })
    }

    let confirmSelfRelation = false;
    if($('[name=status]').length && $('[name=status]').val() == 'cancel' && task.status != 'cancel')
    {
        await $.get($.createLink('task', 'ajaxGetRelation', 'taskIdList=' + task.id), function(data)
        {
            if(data) confirmSelfRelation = true;
        })
    }

    if(confirmParentRelation || confirmSelfRelation)
    {
        let unlinkRelationTip = (!confirmSelfRelation && confirmParentRelation) ? unlinkParentRelationTip : unlinkSelfRelationTip;
        await zui.Modal.confirm({message: unlinkRelationTip, actions: [{key: 'confirm', text: unlinkLang, btnType: 'primary', class: 'btn-wide'}, {key: 'cancel'}]}).then((res) =>
        {
            if(!res) return false;

            const link     = $taskForm.attr('action');
            const formData = new FormData($taskForm[0]);

            $.ajaxSubmit({url: link, data: formData});
        });

        return false;
    }
    return true;
}
