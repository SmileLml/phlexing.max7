$(function()
{
    if($('#demandList thead th.c-title').width() < 150) $('#demandList thead th.c-title').width(150);
    $(document).on('click', '.demand-toggle', function(e)
    {
        var $toggle = $(this);
        var id = $(this).data('id');
        var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
        $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

        e.stopPropagation();
        e.preventDefault();
    });

    toggleFold('#demandForm', unfoldStories, poolID, 'demand');

    adjustTableFooter();
    $('body').on('click', '#toggleFold', adjustTableFooter);
    $('body').on('click', '.icon.icon-angle-double-right', adjustTableFooter);

})

/**
 * Adjust the table footer style.
 *
 * @access public
 * @return void
 */
function adjustTableFooter()
{
    if($('.main-col').height() < $(window).height())
    {
        $('.table.with-footer-fixed').css('margin-bottom', '0');
        $('.table-footer').removeClass('fixed-footer');
        $('.table-footer').css({"left":"0", "bottom":"0", "width":"unset"});
    }
}
