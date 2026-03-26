window.clickSubmit = function()
{
    const isEmpty     = !$('#editEffortForm [name=date]').val() || !$('#editEffortForm [name=consumed]').val() || !$('#editEffortForm [name=work]').val();
    const objectType  = $('#editEffortForm [name=objectType]').val();
    const $left       = $('#editEffortForm [name=left]');
    const left        = $left.val();
    const isEmptyLeft = objectType == 'task' && !$left.prop('readonly') && left == 0;
    if(isEmptyLeft && !isEmpty)
    {
        const formUrl  = $('#editEffortForm form').attr('action');
        const formData = new FormData($("#editEffortForm form")[0]);
        zui.Modal.confirm(noticeFinish).then((res) => {
            if(res) $.ajaxSubmit({url: formUrl, data: formData})
        });
        return false;
    }
}
