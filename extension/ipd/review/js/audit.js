$(function()
{
    var mainHeight = $(window).height() - $('#footer').outerHeight() - $('#header').outerHeight() - 350;
    var sideHeight = mainHeight + 275;
    $('.main-col #reviewcl').css('height', mainHeight);
    $('.side-col .cell').css('height', sideHeight);

    $('table.reviewcl .issueResult, table.reviewcl .resolved').change(function()
    {
        var result = $(this).val();
        if(result == 1)
        {
            $(this).closest('tr').find('.opinion').attr('readonly', true);
            $(this).closest('tr').find('.opinionDate').removeClass('showing').addClass('hidden');
        }
        else
        {
            $(this).closest('tr').find('.opinion').attr('readonly', false);
            $(this).closest('tr').find('.opinionDate').removeClass('hidden').addClass('showing');
        }
    })

    $("#fullScreenBtn").on("click", fullScreenReview())

    function fullScreenReview()
    {
        $("#mainContent").css("z-index", "999999");
    }

    $('textarea').on('mousedown', function(event)
    {
        if(!$(event.currentTarget).prop('readonly')) return;

        event.preventDefault();
    });
})
