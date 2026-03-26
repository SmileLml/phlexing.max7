$(function()
{
    /* Add title for table td. */
    $('.main-table .table tbody tr').each(function()
    {
        $(this).find('td').each(function()
        {
              $(this).attr('title', $(this).text());
        });
    });

    $('table tr td .dropdown .dropdown-menu').closest('td').css('overflow', 'visible');
    $('table tr').each(function(){$(this).find('td:last').removeAttr('title');});
    $('.main-table .table tbody tr').each(function()
    {
        var $aTag = $(this).find('td').eq(0).find('a');
        if($aTag.length > 0) $aTag.prop('outerHTML', $aTag.html());
    });

    if(!$('td.actions').is(':hidden')) {$('td.actions').each(function(){fixOperateMenu($(this));});}

    $(window).resize(function()
    {
        if(!$('td.actions').is(':hidden')) $('td.actions').each(function(){fixOperateMenu($(this));});
    });
});

function fixOperateMenu($td)
{
    var aList = $td.children('a').map(function(){return $(this).outerWidth(true);}).get();

    var $moreMenu = $td.find('.dropdown-menu').parent();
    var tdWidth   = $td.width();
    var aWidth    = aList.length ? aList.reduce(function(a, b){return a + b;}) : 0;
    var mWidth    = $moreMenu.length ? $moreMenu.outerWidth(true) : 0;

    if(tdWidth < aWidth + mWidth)
    {
        if(!aList.length) return false;

        if($moreMenu.length == 0)
        {
            $moreMenu = $("<div id='moreMenu' class='dropdown'><a href='javascript:;' data-toggle='dropdown'>" + window.more + "<span class='caret'></span></a><ul class='dropdown-menu pull-right'></ul></div>");
            $td.append($moreMenu);
        }

        var $lastA = $td.children('a:last');
        $moreMenu.children('.dropdown-menu').prepend($('<li>').append($lastA));
        fixOperateMenu($td);
    }
    else if(tdWidth > aWidth + mWidth + 30)
    {
        if($moreMenu.length == 0) return false;

        var $firstli = $moreMenu.find('.dropdown-menu li:first');
        $moreMenu.before($firstli.html() + ' ');

        $firstli.remove();
        if(!$moreMenu.find('.dropdown-menu li').length) $moreMenu.remove();
    }
}
