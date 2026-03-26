$(function()
{
    $('#libList tbody tr').each(function()
    {
        var $content = $(this).find('td.content');
        var content  = $content.find('div').html();
        if(content.indexOf('<br') >= 0 || content.indexOf('<img') >= 0)
        {
            $content.append("<a href='###' class='more'><i class='icon icon-chevron-double-down'></i></a>");
        }
    });

    $('#fromProduct').change(function()
    {
        link = createLink('assetlib', 'importStory', 'libID=' + libID + '&projectID=' + projectID + '&productID=' + $(this).val());
        location.href = link;
    })

    $('#fromDocLib').change(function()
    {
        var currentMethod = config.currentMethod;
        link = createLink('assetlib', currentMethod, 'libID=' + libID + '&projectID=' + projectID + '&docLibID=' + $(this).val());
        location.href = link;
    })
});

$(document).on('click', 'td.content .more', function(e)
{
    var $toggle = $(this);
    if($toggle.hasClass('open'))
    {
        $toggle.removeClass('open');
        $toggle.closest('.content').find('div').css('height', '25px');
        $toggle.css('padding-top', 0);
        $toggle.find('i').removeClass('icon-chevron-double-up').addClass('icon-chevron-double-down');
    }
    else
    {
        $toggle.addClass('open');
        $toggle.closest('.content').find('div').css('height', 'auto');
        $toggle.css('padding-top', ($toggle.closest('.content').find('div').height() - $toggle.height()) / 2);
        $toggle.find('i').removeClass('icon-chevron-double-down').addClass('icon-chevron-double-up');
    }
});

/**
 * Reload.
 *
 * @param  int    projectID
 * @access public
 * @return void
 */
function reload(projectID)
{
    var currentMethod = config.currentMethod;
    link = createLink('assetlib', currentMethod, 'libID=' + libID + '&projectID=' + projectID);
    location.href = link;
}
