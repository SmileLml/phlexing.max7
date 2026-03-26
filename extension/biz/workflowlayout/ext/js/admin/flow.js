$(function()
{
    setTimeout(function()
    {
        $('[id*=layoutRules]').width('100px');
        $('input[name*=custom]').change();
        $('[name^=readonly]').closest('td').removeClass('w-60px').addClass('w-70px');
    }, 100);

    $('input[name*=custom]').change(function()
    {
        var $span = $(this).parent('span');
        if(!$(this).prop('checked')) $span.find(('select.picker-select[name*=defaultValue]')).show();
    })

    $('select[name*=mobileShow]').val('0').parents('td').hide();

    $('.modal-content .modal-body .form-actions a').attr('target', '_top');
})
