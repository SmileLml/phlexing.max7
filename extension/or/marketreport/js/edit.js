$(function()
{
    if(sourceData == 'outside') $('.showInside').hide();
});

var saveButton      = $('#saveButton');
var saveDraftButton = $('#saveDraftButton');

saveButton.on('click', function(e)
{
    submitReport(e, 'published');
});

saveDraftButton.on('click', function(e)
{
    submitReport(e, 'draft');
});

function submitReport(e, status)
{
    saveButton.attr('disabled', true);
    saveDraftButton.attr('type', 'submit').attr('disabled', true);

    $("#dataform input[name='status']").remove();
    $('<input />').attr('type', 'hidden').attr('name', 'status').attr('value', status).appendTo('#dataform');
    $('#dataform').submit();
    e.preventDefault();

    setTimeout(function()
    {
        if(saveDraftButton.attr('disabled') == 'disabled')
        {
            setTimeout(function()
            {
                saveButton.removeAttr('disabled');
                saveDraftButton.attr('type', 'button').removeAttr('disabled');
            }, 1000);
        }
        else
        {
            saveButton.removeAttr('disabled');
        }
    }, 100);
}

