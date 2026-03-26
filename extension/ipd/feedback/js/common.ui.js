window.like = function(feedbackID)
{
    var likeLink = $.createLink('feedback', 'ajaxLike', 'feedbackID=' + feedbackID);
    $.get(likeLink, function(data)
    {
        $('.detail-actions .toolbar a[key=like]').replaceWith(data);
    });
}
