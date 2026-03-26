window.clickSubmit = function(e)
{
    const $taskBatchForm = $('#taskBatchEditForm' + executionID);
    if(nonStoryChildTasks.length == 0)
    {
        return checkRelation();
    }
    else
    {
        const $taskBatchFormTrs = $taskBatchForm.find('tbody tr');

        var confirmID = '';
        for(let i = 0; i < $taskBatchFormTrs.length; i++)
        {
            const $currentTr = $($taskBatchFormTrs[i]);
            const taskID      = $currentTr.find('.form-batch-control[data-name=id]').find('input[name^=id]').val();
            const storyID     = $currentTr.find('.form-batch-control[data-name=story]').find('input[name^=story]').val();

            if(tasks[taskID].story == storyID) continue;
            if(!storyID && tasks[taskID].parent <= 0) continue;
            if(tasks[taskID].parent > 0)
            {
                if(storyID) confirmID = confirmID.replace('ID' + taskID + ', ', '');
                continue;
            }
            if(typeof childTasks[taskID] != 'object' || typeof nonStoryChildTasks[taskID] != 'object') continue;

            const nonStoryChildTaskIdList = Object.keys(nonStoryChildTasks[taskID]);
            if(nonStoryChildTaskIdList.length == 0) continue;

            for(let j = 0; j < nonStoryChildTaskIdList.length; j++) confirmID += 'ID' + nonStoryChildTaskIdList[j].toString() + ', ';
        }

        if(confirmID.length > 0)
        {
            if(confirmID.endsWith(', ')) confirmID = confirmID.slice(0, -2);

            let confirmTip = syncStoryToChildrenTip.replace('%s', confirmID);
            zui.Modal.confirm(confirmTip).then((res) =>
            {
                $taskBatchForm.find('[name=syncChildren]').remove();
                $taskBatchForm.append('<input type="hidden" name="syncChildren" value="' + (res ? '1' : '0') + '" />');

                if(checkRelation())
                {
                    const formData   = new FormData($taskBatchForm[0]);
                    const confirmURL = $taskBatchForm.attr('action');
                    $.ajaxSubmit({url: confirmURL, data: formData});
                }
            });
        }
        else
        {
            return true;
        }
        return false;
    }
}

function checkRelation()
{
    const $taskBatchForm = $('#taskBatchEditForm' + executionID);

    let checkRelation = false;
    let taskID        = 0;
    let checkTasks    = '';
    $('[name^=status]').each(function()
    {
        taskID = $(this).closest('tr').find('[name^=id]').val();
        if($(this).val() == 'cancel' && tasks[taskID].status != 'cancel')
        {
            checkRelation = true;
            checkTasks   += taskID + ',';
        }
    })
    checkTasks = checkTasks.replace(/^,+|,+$/g, '');

    if(checkRelation && checkTasks)
    {
        let blockSubmit = true;
        $.get($.createLink('task', 'ajaxGetRelation', 'taskIdList=' + checkTasks), function(data)
        {
            if(data)
            {
                zui.Modal.confirm({message: unlinkRelationTip, actions: [{key: 'confirm', text: unlinkLang, btnType: 'primary', class: 'btn-wide'}, {key: 'cancel'}]}).then((res) =>
                {
                    if(res)
                    {
                        const link   = $taskBatchForm.attr('action');
                        let formData = $taskBatchForm.serialize();
                        let postData = {};
                        let params   = new URLSearchParams(formData);

                        for(const[key, value] of params.entries())
                        {
                            const decodedKey   = decodeURIComponent(key);
                            const decodedValue = decodeURIComponent(value);

                            if(!(decodedKey in postData)) postData[decodedKey] = [];
                            postData[decodedKey].push(decodedValue);
                        }

                        $.ajaxSubmit({url: link, data: postData});
                    }
                    else
                    {
                        return false;
                    }
                })
            }
            else
            {
                blockSubmit = false;
            }
        })

        return !blockSubmit;
    }
    return true;
}
