$(function()
{
    $name = $('#batchCreateForm table thead tr th.col-name');
    if($name.width() < 200) $name.width(200);

    $('#customField').click(function()
    {
        hiddenRequireFields();
    });

    /* Implement a custom form without feeling refresh. */
    $('#formSettingForm .btn-primary').click(function()
    {
        saveCustomFields('batchCreateFields', 8, $name, 200);
        return false;
    });
});

$(document).on('click', '.chosen-with-drop', function()
{
    var select = $(this).prev('select');
    if($(select).val() == 'ditto')
    {
        var index = $(select).closest('td').index();
        var row   = $(select).closest('tr').index();
        var table = $(select).closest('tr').parent();
        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }
        $(select).val(value);
        $(select).trigger("chosen:updated");
    }
});
$(document).on('mousedown', 'select', function()
{
    if($(this).val() == 'ditto')
    {
        var index = $(this).closest('td').index();
        var row   = $(this).closest('tr').index();
        var table = $(this).closest('tr').parent();
        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }
        $(this).val(value);
    }
});

$(document).on('change', 'input[name^="undetermined"]', function()
{
    if($(this).is(':checked'))
    {
        $(this).closest('td').find('select[name^="products"]').attr('disabled', 'disabled');
        $(this).closest('td').find('div[id^="products"]').addClass('hidden');
        $(this).closest('td').find('input[name^="undeterminedProduct"]').removeClass('hidden');
        $(this).closest('td').find('select[name^="products"]').trigger('chosen:updated');
    }
    else
    {
        $(this).closest('td').find('select[name^="products"]').removeAttr('disabled');
        $(this).closest('td').find('div[id^="products"]').removeClass('hidden');
        $(this).closest('td').find('input[name^="undeterminedProduct"]').addClass('hidden');
        $(this).closest('td').find('select[name^="products"]').trigger('chosen:updated');
    }
})
