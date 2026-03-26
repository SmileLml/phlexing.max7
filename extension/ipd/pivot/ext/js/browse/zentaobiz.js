$(function()
{
    /* The display of the adjusting sidebarHeader is synchronized with the sidebar. */
    $(".sidebar-toggle").click(function()
    {
        $("#sidebarHeader").toggle("fast");
    });
    if($("main").is(".hide-sidebar")) $("#sidebarHeader").hide();

    if(groupID != 0) $('#module' + groupID).closest('li').addClass('active');

    $('.btn-design').click(function()
    {
        if($(this).hasClass('disabled')) return false;
        result = confirm(confirmDesign);
        if(!result) return false;
    });
});

/**
 * Locate page.
 *
 * @param string module
 * @param string method
 * @param string params
 * @access public
 * @return void
 */
function locate(module, method, params)
{
    var link = createLink(module, method, params);
    window.location.href = link;
}
