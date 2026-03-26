window.latin1ToBase64 = function(str)
{
    const encoder = new TextEncoder();
    const latin1Array  = encoder.encode(str);
    const latin1String = String.fromCharCode.apply(null, latin1Array);
    return btoa(latin1String);
}

window.saveQuery = function()
{
    const data = state();
    if(data.sql) data.sql = latin1ToBase64(data.sql);
    const formData = zui.createFormData({action: 'saveQuery', data: JSON.stringify(data)});
    const link = $('#state').data('url');
    $.ajaxSubmit({url: link, data: formData});
}

window.state = function(key, value)
{
    const state = $('#state').data('state');

    if(key?.length && state.hasOwnProperty(key))
    {
        state[key] = value;
        $('#state').attr('data-state', JSON.stringify(state));
    }

    return state;
}

window.postQueryResult = function(e, info)
{
    const { item } = info;
    const { page } = item;
    const { pager } = state();
    const { pageID, pageTotal } = pager;

    if(page == 'first' && pageID != 1)            pager.pageID = 1;
    if(page == 'last' && pageTotal != pageID)     pager.pageID = pageTotal;
    if(page == 'prev' && pageID - 1 >= 1)         pager.pageID = pageID - 1;
    if(page == 'next' && pageID + 1 <= pageTotal) pager.pageID = pageID + 1;

    state('pager', pager);
    state('triggerQuery', true);
    updatePage('query');
}

window.buildPostParams = function(action, index)
{
    if(!action) action = state().action;
    state('action', action);

    actionSelectors = '#queryBase';

    const data = state();
    if(action.startsWith('sqlBuilder-'))
    {
        const builder = getSqlBuilderPost(action, index);
        data.sqlbuilder = builder.data;
        actionSelectors = builder.selectors;
    }
    if(data.sql) data.sql = latin1ToBase64(data.sql);

    const formData  = zui.createFormData({action, data: JSON.stringify(data)});
    const selectors = [actionSelectors, '#dictionarySideBar', '#state', 'pageJS/.zin-page-js', 'pageCSS/.zin-page-css>*', '#configJS'];
    const link = $('#state').data('url');

    return {link, formData, selectors: selectors.filter(selector => selector?.length != 0).join(',')};
}

window.updatePage = function(action, index)
{
    const {link, formData, selectors} = buildPostParams(action, index);

    postAndLoadPage(link, formData, selectors, {modal: true});
}

window.ajaxQuery = function()
{
    state('triggerQuery', true);
    updatePage('query');
}

window.handleSqlChange = function()
{
    const sql = $('#sqlForm').find('textarea[name="sql"]').val();
    state('sql', sql);
    if(state().mode == 'text')
    {
        const canChangeMode = !sql?.trim()?.length;
        state('canChangeMode', canChangeMode);
        canChangeMode ? $('#changeMode').removeClass('hidden')      : $('#changeMode').addClass('hidden')
        canChangeMode ? $('#changeModeDisabled').addClass('hidden') : $('#changeModeDisabled').removeClass('hidden')
    }
}

window.saveFields = function()
{
    let $form    = $('#fieldSettingsForm');
    let formData = new FormData($form[0]);
    let data = {};
    for (var [key, value] of formData.entries()) {
        const pureKey = key.substring(0, key.indexOf("["));
        if(!data[pureKey]) data[pureKey] = [];
        data[pureKey].push(value);
    }

    let fieldSettings = {};
    data.key.forEach((key, index) => {
        fieldSettings[key] = {};
        Object.keys(data).forEach(itemKey => {
            if(itemKey == 'key') return;
            fieldSettings[key][itemKey] = data[itemKey][index];
        });
    });

    state('fieldSettings', fieldSettings);
    state('triggerQuery', true);

    updatePage('saveFields');
}

window.changeMode = function(event)
{
    const mode = $(event.currentTarget).data('mode');
    const tip  = $(event.currentTarget).data('tip');

    if(mode == 'builder')
    {
        zui.Modal.confirm({message: tip}).then((res) =>
        {
            if(res) doChangeMode(mode);
        });
        return;
    }

    doChangeMode(mode);
}

window.doChangeMode = function(mode)
{
    const changeMode = mode == 'text' ? 'builder' : 'text';
    state('mode', changeMode);
    state('fields', []);
    state('langs', []);
    state('fieldSettings', []);
    state('relatedObject', []);
    state('queryCols', []);
    state('queryData', []);
    state('error', false);
    state('errorMsg', '');

    updatePage('changeMode');

}
