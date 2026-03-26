$(function()
{
    if(batchCloseTip != '') zui.Modal.alert(batchCloseTip);
});

window.renderRowData = function($row, index, row)
{
    if(row.status == 'done')
    {
        $row.find('[data-name="closedReasonBox"]').find('.picker-box').on('inited', function(e, info)
        {
            let $closedReason = info[0];
            $closedReason.render({disabled: true});
        })
    }
}

window.closedReasonChange = function(event)
{
    let closedReason = $(event.target).val();
    let $row         = $(event.target).closest('tr');

    if(closedReason == 'commented') $row.find('[data-name=resolution]').removeAttr('disabled');
    if(closedReason != 'commented') $row.find('[data-name=resolution]').attr('disabled', true);

    $row.find('[data-name=repeatTicket').toggleClass('hidden', closedReason != 'repeat');
    if(closedReason == 'repeat')
    {
        let ticketID       = $row.find('[name^=id]').val();
        let loadTicketLink = $.createLink('ticket', 'ajaxGetRepeatTickets', 'ticketID=' + ticketID);
        $.getJSON(loadTicketLink, function(data)
        {
            let $repeatTicket = $row.find('[name^=repeatTicket').zui('picker');
            $repeatTicket.render({items: data});
        })
    }
}
