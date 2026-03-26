$(function()
{
    $('#ratio').attr('readonly', true);
    $('#pri').attr('disabled', true);
    computeIndex();
    function computeIndex()
    {
        var impact   = $('#impact').val();
        var chance   = $('#chance').val();
        var ratio    = parseInt(impact * chance);
        var pri      = '';
        var priColor = '';
        if(0 < ratio && ratio <= 5)    pri = 'low';
        if(5 < ratio && ratio <= 12)   pri = 'middle';
        if(15 <= ratio && ratio <= 25) pri = 'high';

        if(pri == 'low')    priColor = 'pri-low';
        if(pri == 'middle') priColor = 'pri-middle';
        if(pri == 'high')   priColor = 'pri-high';

        $('#ratio').val(ratio);
        $('#pri').val(pri);
        $('#pri').trigger("chosen:updated")
        $('#pri').chosen();
        $('#pri').attr('disabled', true);
        $('#priValue .chosen-container-single .chosen-single>span').attr("class", priColor);
        $('input[name="pri"]').remove();
        $('#pri').after("<input type='hidden' name='pri' value='" + pri + "'/>");
    }

    $('#impact, #chance').change(function(){computeIndex()});
})
