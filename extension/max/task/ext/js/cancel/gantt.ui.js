window.clickSubmit = function(e)
{
    if(Object.keys(taskRelation).length)
    {
        $.get($.createLink('task', 'ajaxGetRelation', 'taskIdList=' + taskID), function(data)
        {
            if(data)
            {
                zui.Modal.confirm({message: unlinkRelationTip, actions: [{key: 'confirm', text: unlinkLang, btnType: 'primary', class: 'btn-wide'}, {key: 'cancel'}]}).then((res) =>
                {
                    if(res)
                    {
                        const link     = $.createLink('task', 'cancel', 'taskID=' + taskID + '&cardPosition=' + cardPosition + '&from=' + from);
                        const formData = new FormData($('#cancelForm form')[0]);

                        $.ajaxSubmit({url: link, data: formData});
                    }
                    else
                    {
                        return false;
                    }
                })
            }
        })

        return false;
    }
}
