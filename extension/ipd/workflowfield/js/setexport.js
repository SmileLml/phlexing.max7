$(function()
{
    $('[name^=' + window.mode + ']').change(function()
    {
        var checked = $(this).prop('checked');
        $(this).parents('.settingBox').find('.fieldBox').toggle(checked);
        $(this).parents('.settingBox').find('[name^=checkAll], [name^=fields]').prop('checked', checked);
    });

    $('[name^=checkAll]').change(function()
    {
        var checked = $(this).prop('checked');
        $(this).parents('.fieldBox').find('[name^=fields]').prop('checked', checked);
    });

    $('#submit').click(function()
    {
        /* Check if checked the fields to export. */
        var table = '';
        $('[name^=' + window.mode + ']:checked').each(function()
        {
            if($(this).parents('.settingBox').find('[name^=fields]:checked').length == 0)
            {
                table = table == '' ? $(this).data('name') : table + ',' + $(this).data('name');
            }
        });

        if(table == '')
        {
            $('#ajaxForm').submit();
            return false;
        }

        var message = window.emptyExport.replace(/TABLE/, table);
        bootbox.confirm(message, function(result)
        {
            if(result)
            {
                $('#ajaxForm').submit();
            }
        });

        return false;
    });

    $('.sortable .checkbox label').append("<i class='icon-move'></i>");
    $('.sortable').sortable({trigger: '.icon-move'});
});
