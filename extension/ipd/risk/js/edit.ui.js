function changeProject(event)
{
    let projectID = $(event.target).val();
    let link      = $.createLink('project', 'ajaxGetExecutions', 'project=' + projectID + '&mode=leaf');
    $.getJSON(link, function(data)
    {
        let $executionPicker = $('[name="execution"]').zui('picker');
        $executionPicker.render({items: data.items});
        $executionPicker.$.setValue('');

        $('[data-name=execution]').toggleClass('hidden', !data.multiple);
    });
}
