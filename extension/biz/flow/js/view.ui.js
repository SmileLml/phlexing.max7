window.reloadPage = function(e)
{
    let $this = $(e.target);

    $.getJSON($this.attr('href'), function(response)
    {
        if(response.message)
        {
            zui.Modal.alert(response.message).then(() => response.locate ? loadPage(response.locate) : loadCurrentPage());
        }
        else
        {
            response.locate ? loadPage(response.locate) : loadCurrentPage();
        }
    });
    return false;
};

window.linkData = function(e)
{
    let $this    = $(e.target);
    let linkType = $(this).val();
    if(!linkType) return false;

    $('#linkTypeBox').modal('hide', 'fit');

    loadUnlinkData(linkType, 'browse', $this.find('option:selected').text());
};

window.unlinkData = function(obj)
{
    let $this = $(obj);
    let url   = $this.attr('href');

    zui.Modal.confirm($this.data('confirm')).then((res) => {
        if(!res) return false;

        $.getJSON(url, function(response)
        {
            if(response.result == 'success')
            {
                loadCurrentPage();
                return false;
            }
            zui.Modal.alert(response.message);
        })
    });

    return false;
};

$(document).ready(function()
{
    $('.prevP').each(function()
    {
        loadPrevData($(this), 0, 'p');
    });

    $('.prevTR').each(function()
    {
        loadPrevData($(this), 0);
    });

    if(linkType) $('a[href=#' + linkType + ']').trigger('click');
    if(viewMode == 'bysearch') loadUnlinkData(linkType, 'bysearch');
})

function loadUnlinkData(linkType, mode, tabTitle)
{
    tabTitle = typeof tabTitle === 'undefined' ? '' : tabTitle;

    var pane    = $('#' + linkType).length == 1 ? linkType : 'common';
    var $navTab = $('a[href=#' + pane + ']').parent();

    if($navTab.hasClass('hidden'))  $navTab.removeClass('hidden');
    if(!$navTab.hasClass('active')) $navTab.find('a').click();
    if(tabTitle) $navTab.find('a').html(tabTitle);

    $('#querybox').remove();

    var link = loadLink.replace('LINKTYPE', linkType).replace('MODE', mode);
    $.get(link, function(data)
    {
        $('#' + pane).html(data);
        $('#' + pane).find('[data-ride=table]').table();
        $('#' + pane).removeClass('without-search');
        initSearch();
    });
}

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
