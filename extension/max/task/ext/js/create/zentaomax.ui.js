/**
 * 根据选择需求设置查看链接和所属模块。
 * Set preview and module of story.
 *
 * @access public
 * @return void
 */
function setStoryRelated()
{
    $('[name=copyButton]').prop('checked', false);
    setPreview();
    setStoryModule();
    setStoryDesign();
}
