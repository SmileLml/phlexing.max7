$(function()
{
    setTitleWidth()
    $('#feedbackList').resize(function()
    {
        $('#feedbackList th.c-title').width('auto');
        setTitleWidth()
    });
})

function setTitleWidth()
{
    if($('#feedbackList th.c-title').width() <= 130) $('#feedbackList th.c-title').width(130);
}
