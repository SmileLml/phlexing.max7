$(document).ready(function()
{
    $('.singleEdit').click(function()
    {
        $('#attendStat tr:hidden').remove();
        if($('tr.edit').is(':visible')) return false;

        $(this).parents('tr').next('tr.edit').show();
        $(this).parents('tr').next('tr.edit').children('td.singleSave').show();
        $(this).parents('tr').hide();
    })

    if($('#ajaxForm .page-actions #submit').length > 0)
    {
        $('.singleSave').remove();
    }
})
