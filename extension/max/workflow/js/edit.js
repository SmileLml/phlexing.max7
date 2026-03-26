$(document).ready(function()
{
    $('#navigator').change();
    toggleBelong(flowBelong);
    $.setAjaxForm('#editForm', function(response)
    {
        if(response.alert)
        {
            $('#triggerModal [data-dismiss="modal"]').click();
            return bootbox.alert(response.alert, function(){response.locate == 'reload' ? window.location.reload() : window.location.heref = response.locate;});
        }
        if(response.result == 'success') setTimeout(function(){$('#triggerModal [data-dismiss="modal"]').click(); response.locate == 'reload' ? window.location.reload() : window.location.href = response.locate;}, 1200);
    });

    $('.icons').on('click', function()
    {
        const icon = $(this).data('id');

        $('.control-icon').html(`<i class="icon icon-${icon}"></i>`);
        $('input[name=icon]').val(icon);
    });
});
