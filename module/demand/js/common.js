$(function()
{
    $('[data-toggle="popover"]').popover();

    var $saveButton      = $('#saveButton');
    var $saveDraftButton = $('#saveDraftButton');
    $saveButton.on('click', function(e)
    {
        $saveButton.attr('type', 'submit').attr('disabled', true);
        $saveDraftButton.attr('disabled', true);

        var storyStatus = !$('#reviewer').val() || $('#needNotReview').is(':checked') ? 'pass' : 'reviewing';
        $('<input />').attr('type', 'hidden').attr('name', 'status').attr('value', storyStatus).appendTo('#dataform');
        $('#dataform').submit();
        e.preventDefault();

        setTimeout(function()
        {
            if($saveButton.attr('disabled') == 'disabled')
            {
                setTimeout(function()
                {
                    $saveButton.attr('type', 'button').removeAttr('disabled');
                    $saveDraftButton.removeAttr('disabled');
                }, 10000);
            }
            else
            {
                $saveDraftButton.removeAttr('disabled');
            }
        }, 100);
    });

    $saveDraftButton.on('click', function(e)
    {
        $saveButton.attr('disabled', true);
        $saveDraftButton.attr('type', 'submit').attr('disabled', true);

        storyStatus = 'draft';
        if(typeof(page) != 'undefined' && page == 'change') storyStatus = 'changing';
        if(typeof(page) !== 'undefined' && page == 'edit' && $('#status').val() == 'changing') storyStatus = 'changing';
        $('<input />').attr('type', 'hidden').attr('name', 'status').attr('value', storyStatus).appendTo('#dataform');
        $('#dataform').submit();
        e.preventDefault();

        setTimeout(function()
        {
            if($saveDraftButton.attr('disabled') == 'disabled')
            {
                setTimeout(function()
                {
                    $saveButton.removeAttr('disabled');
                    $saveDraftButton.attr('type', 'button').removeAttr('disabled');
                }, 10000);
            }
            else
            {
                $saveButton.removeAttr('disabled');
            }
        }, 100);
    });
});

var products = $('#product').val();

function updateProducts()
{
    if($('#undetermined').is(':checked'))
    {
        $('#undetermined').prop('checked', false);
        toggleProductDropdown();
    }

    var poolID = $('#pool').val();
    var link   = createLink('demand', 'ajaxGetProducts', 'poolID=' + poolID);
    $.post(link, function(html)
    {
        $('#product').closest('.input-group').find('.picker').remove();
        $('#product').replaceWith(html);
        $('#product').picker();
        $('#product').data('zui.picker').setValue(products);
        if(config.currentMethod == 'edit') listenProductClickEvent();
    });
    if(!$('#product').val()) $("#product").siblings(".input-group-addon").show();
}

function changePool(poolID)
{
    loadAssignedTo(poolID);
    loadReviewer(poolID);
}

/**
 * Load assignedTo.
 *
 * @access public
 * @return void
 */
function loadAssignedTo(poolID)
{
    var link = createLink('demand', 'ajaxGetOptions', 'poolID=' + poolID + '&type=assignedTo');
    $.post(link, function(html)
    {
        $('#assignedTo').replaceWith(html);
        $('#assignedToBox .picker').remove();
        $('#assignedTo').picker();
    });
}

function loadReviewer(poolID)
{
    var link = createLink('demand', 'ajaxGetOptions', 'poolID=' + poolID + '&type=reviewer');
    $.post(link, function(html)
    {
        $('#reviewer').replaceWith(html);
        $('#reviewerBox .picker').remove();
        $('#reviewer').picker();
    });
}

function toggleProductDropdown()
{
    if($('#undetermined').is(':checked'))
    {
        $('#product').data('zui.picker').setDisabled(false);
        $('#product').closest('.input-group').find('.picker').hide();
        $('#undeterminedProduct').removeClass('hidden');
    }
    else
    {
        $('#product').data('zui.picker').setDisabled(false);
        $('#product').closest('.input-group').find('.picker').show();
        $('#undeterminedProduct').addClass('hidden');
    }
}

function listenProductClickEvent()
{
    $('div[id^="pk_product"] .picker-selection-remove').on('click', function(event, value)
    {
        var itemID     = $(this).closest('.picker-selection').attr('id');
        var optionID   = itemID.split(/item-(.*?)-/);
        optionID       = parseInt(atob(optionID[1].replace(/-/g, '=')));
        var statusList = ['launched', 'developing', 'closed'];
        if(distributedProducts[optionID])
        {
            if(statusList.includes(distributedProducts[optionID]) && !confirm(confirmChangProduct))
            {
                event.stopPropagation();
            }
        }
    });
}
