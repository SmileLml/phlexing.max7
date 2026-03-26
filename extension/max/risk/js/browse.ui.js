/**
 * 计算表格信息的统计。
 * Set summary for table footer.
 *
 * @param  element element
 * @param  array   checks
 * @access public
 * @return object
 */
window.setStatistics = function(element, checks)
{
    var totalRiskCount  = element.options.data.length;
    var activeRiskCount = hangupRiskCount = 0;
    var summary         = '';

    if(checks.length)
    {
        summary        = checkedSummary;
        totalRiskCount = checks.length;
        checks.forEach((id) => {
            const risk = element.getRowInfo(id).data;
            if(risk.status == 'active') activeRiskCount ++;
            if(risk.status == 'hangup') hangupRiskCount ++;
        });
    }
    else
    {
        summary = pageSummary;
        element.options.data.forEach((risk) => {
            if(risk.status == 'active') activeRiskCount ++;
            if(risk.status == 'hangup') hangupRiskCount ++;
        });
    }

    summary = summary.replace('%total%', totalRiskCount)
        .replace('%active%', activeRiskCount)
        .replace('%hangup%', hangupRiskCount);

    $('.dtable-check-info').attr('title', summary.replace(/<[^>]+>/g,""));
    return {html: summary};
}

window.getCheckedCaseIdList = function()
{
    var riskIdList = '';

    const dtable = zui.DTable.query('#table-risk-browse');
    $.each(dtable.$.getChecks(), function(index, riskID)
    {
        if(index > 0) riskIdList += ',';
        riskIdList += riskID;
    });
    $('#riskIdList').val(riskIdList);
}
