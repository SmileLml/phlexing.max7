$(function()
{
    if(config.requestType == 'GET')
    {
        $('#mainNavbar .nav li, #menu .nav li').removeClass('active').find('[href*=workflow][href*=browseFlow]').parent().addClass('active');
    }
    else
    {
        $('#mainNavbar .nav li, #menu .nav li').removeClass('active').find('[href*=workflow-browseFlow]').parent().addClass('active');
    }
});
