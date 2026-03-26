var defaultProject   = $('#project').prop("outerHTML");
var defaultExecution = $('#execution').prop("outerHTML");

$(function()
{
    if($('#effortList thead th.w-work').width() < 150) $('#effortList thead th.w-work').width(150);
    flushWidth('#sidebar .detail');
    resetWorkWidth();

    if(parseInt($('#product').val()) > 0)
    {
        $('#product').trigger('change');
    }
    else if($('#project') && parseInt($('#project').val()) > 0)
    {
        $('#project').trigger('change');
    }
});

/**
 * Reset work width.
 * .
 * @access public
 * @return void
 */
function resetWorkWidth()
{
    $work = $('#effortForm thead th.c-work');
    if($work.width() < 180) $work.width(180);
}

$('#mainContent .sidebar-toggle').click(function()
{
    setTimeout("resetWorkWidth()", 100);
})
