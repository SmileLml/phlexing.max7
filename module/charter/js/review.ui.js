$(function()
{
    $('.form-row').each(function()
    {
        if($(this).find('.form-group.hidden').length > 0 && $(this).find('.form-group:not(.hidden)').length == 0)
        {
            $(this).addClass('hidden');
            $(this).find('.form-group').removeClass('hidden');
        }
    });
})

window.checkMeeting = function(event)
{
    $('[name=meetingDate]').closest('.form-row').toggleClass('hidden', !$(event.target).prop('checked'));
    $('[name=meetingLocation]').closest('.form-row').toggleClass('hidden', !$(event.target).prop('checked'));
    $('[name=meetingMinutes]').closest('.form-row').toggleClass('hidden', !$(event.target).prop('checked'));
};
