$(function()
{
    var mainHeight = $(window).height() - $('#header').outerHeight() - $('#mainNavbar').outerHeight()- 350;
    var sideHeight = mainHeight + 275;
    $('#reviewcl').css('height', mainHeight);
    $('#reviewRow').css('height', sideHeight);

    $(document).off('mousedown', 'textarea').on('mousedown', 'textarea', function(e)
    {
        if(!$(e.target).prop('disabled')) return;
        e.preventDefault();
    });
});

window.resultChange = function(e)
{
    const result = $(e.target).val();
    const $tr    = $(e.target).closest('tr');
    if(result == 1)
    {
        $tr.find('[name^=issueOpinion]').attr('disabled', true);
        $tr.find('.opinionDate').addClass('hidden');
        $tr.find('[name^=opinionDate]').addAttr('disabled');
    }
    else
    {
        $tr.find('[name^=issueOpinion]').removeAttr('disabled');
        $tr.find('.opinionDate').removeClass('hidden');
        $tr.find('[name^=opinionDate]').removeAttr('disabled');
    }
}

window.auditResultChange = function(e)
{
    $('.form-group[data-name="opinion"] .form-label').toggleClass('required', $(e.target).val() == 'fail');
}
