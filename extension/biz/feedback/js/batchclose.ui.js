window.changeReason = function(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const reason      = $target.val();

    $currentRow.find('[data-name="repeatFeedbackIDList"]').toggleClass('hidden', reason != 'repeat');
}
