$(document).ready(function()
{
    if(typeof window.moduleName !== 'undefined' && (typeof(openInModal) == 'undefined' || openInModal != true))
    {
        $('#navbar .nav li').removeClass('active');
        if(config.requestType == 'GET')
        {
            $('#navbar .nav li a[href*="index\\.php\\?' + config.moduleVar + '\\=' + window.moduleName + '\\&' + config.methodVar + '\\=browse"]').parent('li').addClass('active');
        }
        else
        {
            if(buildin)  $('#navbar .nav li a[href*=\\/' + window.moduleName + '-browse]').parent('li').addClass('active');
            if(!buildin) $('#navbar .nav li a[href*=\\/' + window.moduleName + '-browse-]').parent('li').addClass('active');
        }
    }

    $('[type=file]').each(function()
    {
        var fileName = $(this).closest('.file-input-list').attr('data-filedName');
        var fileID   = fileName.substring(5, fileName.length);
        $(this).closest('.file-input-list').attr('id', fileID);
    })
})

function loadPrevData($selector, dataID, element)
{
    if(typeof dataID  === 'undefined') dataID  = 0;
    if(typeof element === 'undefined') element = 'tr';

    var prev   = $selector.data('prev');
    var next   = $selector.data('next');
    var action = $selector.data('action');
    var field  = $selector.data('field');
    if(dataID == 0) dataID = $selector.data('dataid');

    $('.prevData.' + prev).remove();

    /* Must use flow as module name here because the function ajaxGetPrevData is not a action of a flow. */
    var link = createLink('flow', 'ajaxGetPrevData', 'prev=' + prev + '&next=' + next + '&action=' + action + '&dataID=' + dataID + '&element=' + element);
    $.get(link, function(prevData)
    {
        if(!prevData) return false;

        $selector.after(prevData);
    });
}
