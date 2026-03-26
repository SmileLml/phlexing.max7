/**
 * Load plan info.
 *
 * @param  planID $planID
 * @access public
 * @return void
 */
function loadPlanInfo(planID)
{
    var link = createLink('researchplan', 'ajaxGetPlanInfo', 'planID=' + planID);
    $.get(link, function(data)
    {
        var plan     = JSON.parse(data);
        var zeroData = '0000-00-00 00:00:00';
        if(plan['begin'] == zeroData || plan['end'] == zeroData) plan['begin'] = plan['end'] = '';

        $('#customer').val(plan['customer']);
        $('#begin').val(plan['begin']);
        $('#end').val(plan['end']);
        $('#location').val(plan['location']);
        $('#method').val(plan['method']).trigger('chosen:updated');
    })
}
