$(function()
{
    $('[name^=canSetValue]').change(function()
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
        /* Check if checked the fields to search. */
        var emptyValue = $('[name^=canSetValue]').prop('checked') && $('[name^=fields]:checked').length == 0;

        if(!emptyValue)
        {
            $('#ajaxForm').submit();
            return false;
        }

        bootbox.confirm(window.emptyValue, function(result)
        {
            if(result)
            {
                $('#ajaxForm').submit();
            }
        });

        return false;
    });
});
