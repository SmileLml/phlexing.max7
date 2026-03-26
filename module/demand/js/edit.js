$(function()
{
    $('#needNotReview').on('change', function()
    {
        $('#reviewer').attr('disabled', $(this).is(':checked') ? 'disabled' : null).trigger('chosen:updated');
    });

    if(!$('#reviewer').val()) $('#needNotReview').change();

    // init pri selector
    $('#pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });

    toggleProductDropdown();

    $('#pool').on('change', function()
    {
        $('#undetermined').prop('checked', false);
        toggleProductDropdown();
        updateProducts();
    });

    listenProductClickEvent();

    if($('#product').val()) $("#product").siblings(".input-group-addon").hide();
})
