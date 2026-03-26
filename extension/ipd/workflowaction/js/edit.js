$(function()
{
    $('#actionTable [name=status]').change(function()
    {
        $('#actionTable tr:not(.nameTR, .statusTR, .submitTR)').toggle($('[name=status]:checked').val() == 'enable');
    });

    $('#actionTable [name=status]').change();
    $('#actionTable #type').change();
})
