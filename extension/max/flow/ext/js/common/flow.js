/* Adjust for menu active. */
$(function()
{
    if(typeof window.moduleName !== 'undefined' && config.requestType != 'GET' && (typeof(openInModal) == 'undefined' || openInModal != true))
    {
        $('#navbar .nav li a[href*=\\/' + moduleName + '-browse]').parent('li').addClass('active');
    }
})
