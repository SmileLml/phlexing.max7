$(function()
{
    /* Set default tab. */
    if($.cookie('recordEstimateType') == 'all')
    {
        $('#createEffort').addClass('hidden');
        $('.my-effort, #legendMyEffort').removeClass('active');
        $('.all-effort, #legendAllEffort').addClass('active');
    }
    else
    {
        $('.my-effort, #legendMyEffort').addClass('active');
        $('#createEffort').removeClass('hidden');
    }
    $.cookie('recordEstimateType', null);

    $('.order-btn').on('click', function()
    {
        $.cookie('recordEstimateType', 'all');
    });

    /* Hide creation logs when displaying team logs. */
    $('#linearefforts .tabs ul > li').click(function()
    {
        var tab = $(this).find('a').attr('href');
        $('#createEffort').toggleClass('hidden', tab == '#legendAllEffort');
    });

    $("#submit").click(function(e, confirmed)
    {
        if(confirmed) return true;

        var $this = $(this);
        var hasZero = false;
        var $left   = $("input[name^='left']");
        $left.each(function()
        {
            if($(this).attr('disabled') != 'disabled' && !$(this).prop('readonly') && parseFloat($(this).val(), 10) === 0) hasZero = true;
        })
        if(hasZero)
        {
            e.preventDefault();
            bootbox.confirm(noticeFinish, function(result)
            {
                if(!result) $this.attr("disabled", false);
                if(result) $this.trigger('click', true);
            });
            return false;
        }
    });

    if(objectType == 'task' || objectType == 'story') $('.form-date').datetimepicker('setEndDate', today);

    $('#objectTable .showinonlybody').each(function()
    {
        $(this).click(function()
        {
            var hasRecord = false;
            $('#objectTable').find('input[name^="consumed"], input[name^="left"], input[name^="work"]').each(function()
            {
                if($(this).val() !== '')
                {
                    hasRecord = true;
                    return false;
                }
            });
            if(hasRecord)
            {
                alert(noticeSaveRecord);
                return false;
            }
        });
    });

    $('.btn-back').click(function()
    {
        $.closeModal();
        return false;
    });

    $('.table-record .date-group .input-group-addon').on('click', function()
    {
        $(this).prev().datetimepicker('show');
    });


    $('#toggleFoldIcon').click(function()
    {
        var fold  = $(this).find('.icon-angle-down').length > 0;
        var $icon = $(this).find('.icon-border > i');
        var $text = $(this).find('.text');

        $.cookie('taskEffortFold', fold ? 0 : 1);

        if(fold)
        {
            /* Update icon and text. */
            $icon.removeClass('icon-angle-down')
                .addClass('icon-angle-top');
            $text.text(foldEffort);

            /* Show all efforts. */
            $('.taskEffort > tbody > tr').removeClass('hidden');
        }
        else
        {
            $icon.removeClass('icon-angle-top')
                .addClass('icon-angle-down');
            $text.text(unfoldEffort);

            /* Efforts whose number is greater than 3 are hidden. */
            $('.taskEffort > tbody > tr').filter(':gt(2)').addClass('hidden');
        }
    });
})
