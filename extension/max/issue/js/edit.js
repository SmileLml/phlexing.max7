$('#project').change(function()
{
    var link = createLink('project', 'ajaxGetExecutions', 'project=' + $(this).val() + '&executionID=0&mode=leaf');
    $('td.executionBox').load(link, function(){$('#execution').change().chosen();});

    link = createLink('risk', "ajaxGetProjectRisks", "projectID=" + $(this).val());
    if($(this).val() == projectID) link = createLink('risk', "ajaxGetProjectRisks", "projectID=" + $(this).val() + '&append=' + risk);
    $('td.riskBox').load(link, function(){$('#risk').val(risk).chosen();});
});
