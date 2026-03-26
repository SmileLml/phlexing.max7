var eventInitiator = '';
$('#market').on('select', function(event, changeValue) {
    eventInitiator = 'market';
});
$('#research').on('select', function(event, changeValue) {
    eventInitiator = 'research';
});

$('#market').on('change', function(event, changeValue) {
    if(changeValue.value)
    {
        $('#market').next().find('.picker-selection-remove').css('display','flex');
    }
    else
    {
        $('#market').next().find('.picker-selection-remove').css('display','none');
    }

    var picker = $('#research').data('zui.picker');

    var link = createLink('marketreport', 'ajaxGetResearchList', 'marketID=' + parseInt(changeValue.value ? changeValue.value : '0'));
    $.get(link, function(data)
    {
        picker.updateOptionList(JSON.parse(data), true);
        if(eventInitiator == 'market') $('#research').data('zui.picker').setValue('');
    });
});

$('#research').on('change', function(event, changeValue) {
    if(changeValue.value)
    {
        $('#research').next().find('.picker-selection-remove').css('display','flex');
    }
    else
    {
        $('#research').next().find('.picker-selection-remove').css('display','none');
    }
    var link = createLink('marketreport', 'ajaxGetMarketList', 'researchID=' + parseInt(changeValue.value ? changeValue.value : '0'));
    var picker = $('#market').data('zui.picker');
    $.get(link, function(data)
    {
        data = JSON.parse(data);
        picker.updateOptionList(data, true);
        if(data.length == 1) picker.setValue(data[0].value);
    });
});

$(function()
{
    $(document).on('change', '[name=source]', function()
    {
        const source = $('[name=source]:checked').val();
        if(source == 'outside')
        {
            $('.showInside').hide();
        }
        else
        {
            $('.showInside').show();
        }

    });
});

