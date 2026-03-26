function handleCreateScreen(screenID)
{
    parent.zui.Modal.hide();
    parent.openUrl(createLink('screen', 'design', 'screenID=' + screenID), {app: 'bi'})
}
