window.changeSource = function()
{
    const source = $('[name=source]').val();
    if(source == 'researchreport')
    {
        let items = [];
        $.each(reportPairs, function(reportID, reportName)
        {
            items.push({text: reportName, value: reportID});
        });
        $('.sourceNoteBox th').text(reportLang);
        $('.sourceNoteBox td').children().replaceWith("<div id='sourceNote' class='form-group-wrapper picker-box'></div>");
        new zui.Picker('#sourceNote', {name: 'sourceNote',  items: items});
    }
    else
    {
        $('.sourceNoteBox th').text(sourceNoteLang);
        $('.sourceNoteBox td').children().replaceWith("<input class='form-control' type='text' name='sourceNote' />");
    }
}
