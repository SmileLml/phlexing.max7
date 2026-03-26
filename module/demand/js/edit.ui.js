window.changeNeedNotReview = function()
{
    const isChecked = $('[name=needNotReview]').prop('checked');
    const $reviewer = $('[name^=reviewer]').zui('picker');

    if(isChecked)
    {
        $('[name=needNotReview]').val(1);
        $reviewer.render({disabled: true});
    }
    else
    {
        $('[name=needNotReview]').val(0);
        $reviewer.render({disabled: false});
    }
};

window.changePool = function()
{
    $('#undetermined').prop('checked', false);
    toggleProductDropdown();
    updateProducts();
}

$(function()
{
    window.waitDom("[name^=product]", function(){toggleProductDropdown()});

    window.waitDom('[name^="reviewer"]', function(){if(!$('[name^="reviewer"]').val().filter(Boolean).length) changeNeedNotReview();})

    if($("[name^=product]").val()) $("#product").siblings(".input-group-addon").hide();
})

window.changeReviewer = function()
{
    var $reviewer     = $('[name^="reviewer"]');
    var reviewerCount = $reviewer.val().filter(Boolean).length;
    var $value        = $reviewer.val();

    const filteredArray = reviewedBy.filter(value => value !== '');
    const isContained   = filteredArray.every(element => $value.includes(element));

    if(!isContained)
    {
        zui.Modal.alert(notDeleted);
        $reviewer.zui('picker').$.setValue($value.concat(reviewedBy));
        return;
    }

    if(demandStatus == 'reviewing')
    {
        if(!reviewerCount)
        {
            zui.Modal.alert(reviewerNotEmpty);
            if(typeof(lastSeletedReviewer) == 'undefined') lastSeletedReviewer = demandReviewers.join();
            $reviewer.zui('picker').$.setValue(lastSeletedReviewer);
        }
        else
        {
            lastSeletedReviewer = $reviewer.val();
        }
    }
    else
    {
        if(!reviewerCount)
        {
            $('#needNotReview').prop('checked', true);
            changeNeedNotReview($('#needNotReview'));
        }
    }
}
