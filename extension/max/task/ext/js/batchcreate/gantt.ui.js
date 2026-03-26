window.clickSubmit = function(e)
{
    let hasData = false;
    $('[name^=name]').each(function()
    {
        if($(this).val())
        {
            hasData = true;
            return false;
        }
    })

    if(splitTaskRelation && Object.keys(splitTaskRelation).length > 0 && hasData)
    {
        zui.Modal.confirm({message: unlinkRelationTip, actions: [{key: 'confirm', text: unlinkLang, btnType: 'primary', class: 'btn-wide'}, {key: 'cancel'}]}).then((res) =>
        {
            if(res)
            {
                const link   = $('#taskBatchCreateForm > .panel-body form').attr('action');
                let formData = $('#taskBatchCreateForm > .panel-body form').serialize();
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

        return false;
    }
}
