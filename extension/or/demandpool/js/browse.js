$(function()
{
    $('#opinionList td.has-child .opinion-toggle').each(function()
    {
        var $td = $(this).closest('td');
        var labelWidth = 0;
        if($td.find('.label').length > 0) labelWidth = $td.find('.label').width();
        $td.find('a').eq(0).css('max-width', $td.width() - labelWidth - 60);
    });
})

$(document).on('click', '.table-nest-icon', function(e)
{   
    var $toggle = $(this);
    var id = $(this).data('id');
    var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
    $toggle.closest('form').find('tr.parent-' + id).toggle(!isCollapsed);
    if(!isCollapsed)
    {
        $(this).removeClass('table-nest-child-hide');
    }
    else
    {
        $(this).addClass('table-nest-child-hide');
    }

    e.stopPropagation();
    e.preventDefault();
});
