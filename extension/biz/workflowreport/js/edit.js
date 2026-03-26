$(document).ready(function()
{
    toggleSelect();

    /* Display fields of current module when dimension selected. */
    $('#granularity_chosen').hide();
    $('#dimension_chosen a').addClass('dimension-all-radius');
    $('#dimension').change();

    $("input[name='countType'][type='radio']").change();
});
