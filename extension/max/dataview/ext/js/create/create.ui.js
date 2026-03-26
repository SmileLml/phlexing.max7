window.createDataview = function()
{
    $(`#createForm span.text-danger`).addClass('hidden');
    const link = $('#state').data('url');

    const data = state();
    const formData  = zui.createFormData({action: 'save', data: JSON.stringify(data)});
    $.post(link, formData, function(resp)
    {
        resp = JSON.parse(resp);
        if(resp.result == 'success')
        {
            const [method, params] = resp.callback.params;
            const link = $.createLink('dataview', method, params);
            loadPage(link);
        }
        else
        {
            Object.keys(resp.message).forEach(name => {
                setError(name, resp.message[name][0]);
            });
        }
    });
}

window.handleChangeGroup = function(event)
{
    const $target = $(event.target);
    const value   = $target.val();
    state('group', value);
}

window.handleChangeName = function(event)
{
    const $target = $(event.target);
    const value   = $target.val();
    state('name', value);
}

window.handleChangeCode = function(event)
{
    const $target = $(event.target);
    const value   = $target.val();
    state('code', value);
    state('view', 'ztv_' + value);
}

window.setError = function(name, error)
{
    if(name == 'view') name = 'code';
    $(`#createForm #${name}Control span.text-danger`).removeClass('hidden').html(error);
}
