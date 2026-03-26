window.changeNeedReview = function()
{
    const checked = $(this).is(':checked');

    $('[name^=reviewer]').zui('picker').$.setValue(checked ? '' : lastReviewer);
    $('[name^=reviewer]').zui('picker').render({disabled: checked});

    if(checked)
    {
        $(this).closest('.form-group').find('.form-label').removeClass('required');
    }
    else
    {
        $(this).closest('.form-group').find('.form-label').addClass('required');
    }
}
