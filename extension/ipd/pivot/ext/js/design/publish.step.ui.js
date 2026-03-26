window.changePivot = function(event, key)
{
    const value = $(event.target).val();
    pivotState(key, value);
    if(['name', 'desc'].includes(key))
    {
        const lang = pivotState().clientLang;
        const keys = pivotState()[key + 's'];
        keys[lang] = value;
        pivotState(key + 's', keys);
    }
    setChanged();
}

window.changePivotLang = function(event, key)
{
    const value = $(event.target).val();
    const lang  = $(event.target).parent().data('lang')
    const keys = pivotState()[key + 's'];
    keys[lang] = value;
    pivotState(key + 's', keys);

    if(pivotState.clientLang == lang) pivotState(key, value);
    setChanged();
}

window.changePivotAcl = function(event)
{
    const acl = $(event.target).val();
    pivotState('acl', acl);
}

window.changePivotWhitelist = function(event)
{
    const whitelist = $(event.target).val().join();
    pivotState('whitelist', whitelist);
}

window.saveInfo = function()
{
    setChanged(false);
    updateDesignPage('saveInfo');
}
