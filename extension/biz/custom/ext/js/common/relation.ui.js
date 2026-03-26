window.clickSubmit = function()
{
    let existedName = '';
    $('[name^=relation]').each(function()
    {
        if(allRelationName['relation'].includes($(this).val())) existedName += ' ' + $(this).val();
    });
    $('[name^=relativeRelation]').each(function()
    {
        if(allRelationName['relativeRelation'].includes($(this).val())) existedName += ' ' + $(this).val();
    });

    if(existedName.length)
    {
        const formUrl  = $('#relationForm').attr('action');
        const formData = new FormData($("#relationForm")[0]);
        const confirmMessage = hasRelationTip.replace('%s', existedName);
        zui.Modal.confirm(confirmMessage).then((res) => {
            if(res) $.ajaxSubmit({url: formUrl, data: formData})
        });
        return false;
    }
}
