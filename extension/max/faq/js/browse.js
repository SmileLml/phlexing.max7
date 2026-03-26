$(function()
{
    var $ol = $('ol');
    $('#toggleToc').click(function()
    {
        $('ol').toggle();
        if($ol.is(":visible"))
        {
            $('#toggleToc span').text(hidden);
        }
        else
        {
            $('#toggleToc span').text(show);
        }
    });
});
