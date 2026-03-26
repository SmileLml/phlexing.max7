$(function()
{
    if(typeof zentaoApp !== 'undefined')
    {
        $('.progress-container').remove();
        $('#setting').remove();
    }

    setTimeout(function()
    {
        loadTarget($.createLink('instance', 'ajaxGetCustoms', 'appID=0&instanceID=' + instanceID), '#instanceCustomFieldsBlock');
    }, 100);
});
