$(function()
{
    $('#navigator').change();
    toggleBelong(flowBelong);
    $.setAjaxForm('#releaseForm', function(response)
    {
        if(response.alert)
        {
            $('#triggerModal [data-dismiss="modal"]').click();
            return bootbox.alert(response.alert, function(){top.location.href = response.locate});
        }
        if(response.result == 'success') setTimeout(function(){top.location.href = response.locate;}, 1200);
    });
})
