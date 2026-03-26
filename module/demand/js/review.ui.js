window.changeResult = function()
{
    let $value = event.target.value;
    $('#priBox').toggleClass('hidden', $value != 'pass');
    $('#closedReasonBox').toggleClass('hidden', $value != 'reject');
    $('#assignedToBox').toggleClass('hidden', $value == 'reject');
}

window.changeReason = function()
{
    $('#duplicateDemandBox').toggleClass('hidden', event.target.value != 'duplicate');
}
