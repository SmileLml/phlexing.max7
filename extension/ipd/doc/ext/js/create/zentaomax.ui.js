window.loadContent = function()
{
    $('#modalTemplate').modal('hide');

    const templateID = $('#modalTemplate [name=template]').val();
    if(templateID == '') return;

    const link = $.createLink('baseline', 'ajaxGetContent', 'templateID=' + templateID);
    $.getJSON(link, function(data)
    {
        $editor = $('zen-editor[name=content]');
        if($editor.val() == '')
        {
            $editor[0].setHTML(data.content);
        }
        else
        {
            zui.Modal.confirm(replaceContentTip).then((res) => {
                if(res) $editor[0].setHTML(data.content);
            });
        }
    })
}
