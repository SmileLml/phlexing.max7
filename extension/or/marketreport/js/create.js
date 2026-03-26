var saveButton      = $('#saveButton');
var saveDraftButton = $('#saveDraftButton');

function addNewMarket()
{
    var picker = $('#research').data('zui.picker');
    if($('#marketBox input').css('display') != 'none')
    {
        picker.setValue('');
        picker.setDisabled(true);
        $('#research').next().addClass('disabled');
    }
    else
    {
        $('#research').next().removeClass('disabled');
        picker.setDisabled(false);
        $('#market').data('zui.picker').setValue('');
    }
    $('#marketBox .picker').toggle();
    $('#marketBox input').css('border-left-color', '');
    $('#marketBox input').toggle();
}

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

