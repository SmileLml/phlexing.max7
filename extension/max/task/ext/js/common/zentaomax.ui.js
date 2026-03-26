/**
 * 设置设计下拉范围。
 * Set story design.
 *
 * @access public
 * @return void
 */
function setStoryDesign()
{
    const $design = $('input[name=design]');
    if($design.length == 0) return;

    const storyID   = $('input[name="story"]').val();
    const designID  = $design.val();
    const execution = typeof executionID != 'undefined' ? executionID : $('input[name=execution]').val();
    const link      = $.createLink('story', 'ajaxGetDesign', 'storyID=' + storyID + '&designID=' + designID + '&executionID=' + execution);
    $.getJSON(link, function(data)
    {
        const $designPicker = $('input[name=design]').zui('picker');
        $designPicker.render({items: data});
        $designPicker.$.setValue(designID);
    });
}
