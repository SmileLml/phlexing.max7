/**
 * Ajax get search form.
 *
 * @param  string   $queryBox
 * @param  callback $callback
 * @access public
 * @return void
 */
function ajaxGetSearchForm($queryBox, callback)
{
    if(!$queryBox) $queryBox = $('#querybox');
    if($queryBox.html() == '')
    {
        var module = $queryBox.data('module');
        $.get(createLink('search', 'buildOldForm', 'module=' + module), function(data)
        {
            $queryBox.html(data);
            callback && callback();
        });
    }
}

/**
 * Init search form.
 *
 * @access public
 * @return void
 */
function initSearch()
{
    $searchTab = $('#bysearchTab');
    if($searchTab.data('initSearch')) return;

    if(!$searchTab.closest('#menu').length)
    {
        $('#menu>.container>.nav:first').append($searchTab);
    }

    var $queryBox = $('#querybox');
    if(!$queryBox.length)
    {
        $queryBox = $("<div id='querybox' class='hidden'/>").insertAfter($('#menu'));
    }

    if(mode == 'bysearch')
    {
        $('#menu > ul > li.active').removeClass('active');
        ajaxGetSearchForm($queryBox);
        $searchTab.addClass('active').find('a').attr('href', '#bysearch');
        $queryBox.removeClass('hidden');
    }

    $searchTab.on('click', function()
    {
        var isSearchTabActive = $searchTab.hasClass('active');
        if(isSearchTabActive)
        {
            var $oldTab = $searchTab.data('oldTab');
            if($oldTab)
            {
                $oldTab.addClass('active');
            }
            else
            {
                $searchTab.addClass('selected');
            }
        }
        else
        {
            $(window).scrollTop(0);
            $searchTab.data('oldTab', $('#menu > ul > li.active').removeClass('active'));
            ajaxGetSearchForm($queryBox, function()
            {
                if(!$queryBox.hasClass('hidden')) $queryBox.trigger('querybox.toggle', true);
            });
        }
        $searchTab.toggleClass('active', !isSearchTabActive);
        $queryBox.toggleClass('hidden', isSearchTabActive).trigger('querybox.toggle', !isSearchTabActive);
    });

    $searchTab.data('initSearch', true);
}
