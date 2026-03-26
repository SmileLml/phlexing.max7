/**
 * Set duplicate field.
 *
 * @param  string $resolution
 * @param  int    $feedbackID
 * @access public
 * @return void
 */
function setDuplicate(resolution, feedbackID)
{
    if(resolution == 'repeat')
    {
        $('#duplicateFeedbackBox' + feedbackID).show();
    }
    else
    {
        $('#duplicateFeedbackBox' + feedbackID).hide();
    }
}

$(function()
{
    $('select[id^="repeatFeedbackIDList"]').picker(
    {
        disableEmptySearch : true,
        dropWidth : 'auto',
        onReady: function(event)
        {
            $(event.picker.$container).addClass('required');
        }

    });
});
