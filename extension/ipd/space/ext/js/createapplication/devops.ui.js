const changeStoreAppMethod = window.onChangeStoreAppType;
window.onChangeStoreAppType = function(event)
{
    changeStoreAppMethod(event);

    let storeApp = appID;
    if(!storeApp)
    {
        if(typeof(event) == 'undefined')
        {
            storeApp = defaultApp;
        }
        else
        {
            storeApp = $('[name=storeAppType]').val();
        }
    }

    loadTarget($.createLink('instance', 'ajaxGetCustoms', 'appID=' + storeApp), '#customField');
}
