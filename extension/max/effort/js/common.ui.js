function setLeftInput(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const left        = $target.val();
    if(left.indexOf('task_') >= 0)
    {
        $currentRow.find('[data-name=left] input').attr('disabled', false);
        $currentRow.find('[data-name=left] input').removeClass('disabled');
    }
    else
    {
        $currentRow.find('[data-name=left] input').attr('disabled', true);
        $currentRow.find('[data-name=left] input').addClass('disabled');
    }
}
