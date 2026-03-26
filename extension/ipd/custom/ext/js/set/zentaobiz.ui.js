window.changeNeedReview = function()
{
    if($('[name=needReview]:checked').val() == 0)
    {
        $('[name^=forceReview]').closest('.form-group').removeClass('hidden');
        $('[name^=forceNotReview]').closest('.form-group').addClass('hidden');
    }
    else
    {
        $('[name^=forceReview]').closest('.form-group').addClass('hidden');
        $('[name^=forceNotReview]').closest('.form-group').removeClass('hidden');
    }
}
