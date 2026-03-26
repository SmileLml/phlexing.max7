window.reasonChange = function()
{
    const reason = $('[name=closedReason]').val();
    $('.repeatFeedbackBox').toggleClass('hidden', reason !== 'repeat');
}
