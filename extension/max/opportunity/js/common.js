$(function()
{
    if(config.currentMethod == 'create' || config.currentMethod == 'edit' || config.currentMethod == 'track')
    {
        computeIndex();
        $('#impact, #chance').change(function(){computeIndex()});
    }

    if(config.currentMethod == 'batchcreate')
    {
        $("[id^=impact]").each(function()
        {
            if(this.id != 'impact%s') computeIndex(this);
        })
    }

    if(config.currentMethod == 'batchedit')
    {
        $("[id^=impact]").each(function()
        {
            computeIndex(this);
        })

        $("[id^='impact'], [id^='chance']").change(function(){computeIndex(this)});
    }
})

$('#importLinesBtn').on('click', function()
{
    setTimeout(function()
    {
        $("[id^=priValue]").each(function()
        {
            $('select[id^=pri]').trigger('chosen:updated');
            $(this).find("[id$='_chosen']").find('span').addClass('pri-middle');
        })
    }, 500);
});

/**
 * computeIndex
 *
 * @param  object obj
 * @param  int    number
 * @access public
 * @return void
 */
function computeIndex(obj = '', number = '')
{
    if(obj)
    {
        var selectID = obj.id;
        if(!number) var number = $('#' + selectID).attr('data-number');
        var impact = $('#impact' + number).val();
        var chance = $('#chance' + number).val();
    }
    else
    {
        var impact = $('#impact').val();
        var chance = $('#chance').val();
    }

    var ratio    = parseInt(impact * chance);
    var pri      = '';
    var priColor = '';
    if(0 < ratio && ratio <= 5)    pri = 'low';
    if(5 < ratio && ratio <= 12)   pri = 'middle';
    if(15 <= ratio && ratio <= 25) pri = 'high';

    if(pri == 'low')    priColor = 'pri-low';
    if(pri == 'middle') priColor = 'pri-middle';
    if(pri == 'high')   priColor = 'pri-high';

    if(obj)
    {
        $('#ratio' + number).val(ratio);
        $('#pri' + number).val(pri);
        $('#pri' + number).trigger("chosen:updated")
        $('#pri' + number).chosen();
        $('#pri' + number).attr('disabled', true);
        $('#priValue' + number +' .chosen-container-single .chosen-single>span').attr("class", priColor);
        $('input[name="pri[' + number + ']"]').remove();
        $('#pri' + number).after("<input type='hidden' name='pri[" + number + "]' value='" + pri + "'/>");
    }
    else
    {
        $('#ratio').val(ratio);
        $('#pri').val(pri);
        $('#pri').trigger("chosen:updated")
        $('#pri').chosen();
        $('#pri').attr('disabled', true);
        $('#priValue .chosen-container-single .chosen-single>span').attr("class", priColor);
        $('input[name="pri"]').remove();
        $('#pri').after("<input type='hidden' name='pri' value='" + pri + "'/>");
    }
}
