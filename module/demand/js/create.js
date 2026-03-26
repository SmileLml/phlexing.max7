$(function()
{
    if(from == 'feedback') $("#navbar .nav li[data-id=browse]").addClass('active');
    $('#needNotReview').on('change', function()
    {
        $('#reviewer').attr('disabled', $(this).is(':checked') ? 'disabled' : null).trigger('chosen:updated');

        if($(this).is(':checked'))
        {
            $('#reviewerBox').closest('tr').addClass('hidden');
            $('#reviewerBox').removeClass('required');
            $('#dataform #needNotReview').val(1);
            $('#dataform .form-actions .needNotReview').removeClass('hidden');
            $('#dataform .form-actions .needReview').addClass('hidden');
        }
        else
        {
            $('#reviewerBox').closest('tr').removeClass('hidden');
            $('#reviewerBox').addClass('required');
            $('#dataform #needNotReview').val(0);
            $('#dataform .form-actions .needNotReview').addClass('hidden');
            $('#dataform .form-actions .needReview').removeClass('hidden');
        }
    });
    $('#needNotReview').change();


    // init pri selector
    $('#pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });

    $('#customField').click(function()
    {
        hiddenRequireFields();
    });

    /* Implement a custom form without feeling refresh. */
    $('#formSettingForm .btn-primary').click(function()
    {
        saveCustomFields('createFields');
        return false;
    });

    $('#pool').on('change', function()
    {
        var poolID = $(this).val();
        link = createLink('demand', 'ajaxGetParentDemands', 'poolID=' + poolID);
        $.post(link, function(data)
        {
            if(!data) data = '<select id="parent" name="parent" class="form-control"></select>';
            $('#parent').replaceWith(data);
            $('#parent_chosen').remove();
            $("#parent").chosen();
        })

        loadReviewer(poolID);
        if(isCopy) updateProducts();
        loadAssignedTo(poolID)
    });

    if(isCopy && isUndetermined)
    {
        $('#undetermined').prop('checked', true);
        toggleProductDropdown();
    }
})

function loadAssignedTo(poolID)
{
    var link = createLink('demand', 'ajaxGetAssignedTo', 'poolID=' + poolID + '&type=assignedTo');
    $.post(link, function(html)
    {
        $('#assignedTo').replaceWith(html);
        $('#assignedToBox .picker').remove();
        $('#assignedTo').picker();
    });
}

function save(obj, demandStatus = '')
{
    event.preventDefault();
    if(demandStatus) $('<input />').attr('type', 'hidden').attr('name', 'status').attr('value', demandStatus).appendTo('#dataform');
    $(obj).attr('type', 'submit');
    $(obj).parent().find('button').attr('disabled', true);

    $('#dataform').submit();

    setTimeout(function()
    {
        if($(obj).attr('disabled') == 'disabled')
        {
            setTimeout(function()
            {
                $(obj).attr('type', 'button').removeAttr('disabled');
                $(obj).parent().find('button').removeAttr('disabled');
            }, 10000);
        }
        else
        {
            $(obj).parent().find('button').removeAttr('disabled');
        }
    }, 100);
}
