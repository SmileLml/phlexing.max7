/**
 * Set story design.
 *
 * @access public
 * @return void
 */
function setStoryDesign(designID)
{
    var storyID     = $('#story').val() ? $('#story').val() : 0;
    var executionID = $('#execution').val();
    designID        = typeof(designID) == 'undefined' ? 0 : designID;

    var link = createLink('story', 'ajaxGetDesign', 'storyID=' + storyID + '&designID=' + designID + '&executionID=' + executionID);
    $.post(link, function(data)
    {
        $('#design').replaceWith(data);
        $('#design_chosen').remove();
        $('#design').chosen();
    });
}

/**
 * Set story related.
 *
 * @param  int $num
 * @access public
 * @return void
 */
function setStoryRelated(num)
{
    if(typeof num === 'undefined')
    {
        setPreview();
        setStoryModule();
        setStoryDesign();
    }
    else
    {
        setPreview(num);
    }
}
