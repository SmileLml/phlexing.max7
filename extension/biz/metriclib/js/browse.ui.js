window.renderCell = function(result, {row, col})
{
    return result;
}

window.renderHeaderCell = function(result, {row, col})
{
    var text = result[0];
    var html = `<span title="${text}">${text}</span>`;

    result[0] = {html: html};
    return result;
}

window.getCurrentModal = function()
{
    target = zui.Modal.query().id;
    target = `#${target}`;

    return zui.Modal.query(target);
}

window.loadImplement = function(link)
{
    const modal = window.getCurrentModal();
    if(!modal) return;

    $("#" + modal.id).attr('load-url', link);
    modal.render({url: link});
}

window.handleFilter = function(scope = 'project', period = 'nodate',viewType = 'history')
{
    const formData = new FormData($('#queryForm')[0]);
    postAndLoadPage($.createLink('metriclib', 'browse', `scope=${scope}&period=${period}&viewType=${viewType}&isClearFilter=0`), formData, 'dtable/#library:component');
}

window.loadScopeOptions = function(scope)
{
    const parentIdList = $('#parentPicker .picker-select').zui('picker').$.value;
    const link = $.createLink('metriclib', 'ajaxGetFilterOptions', 'scope=' + scope + '&parentList=' + parentIdList);

    $.getJSON(link, function(options)
    {
        $scopePicker = $('#scopePicker .picker-select').zui('picker');

        let scopeValues = $scopePicker.$.value.split(',').map(Number);
        let loadedScopeList = options.map(obj => obj.value);
        let intersection = loadedScopeList.filter(item => scopeValues.includes(item));

        $scopePicker.render({multiple: true, items: options});
        $scopePicker.$.setValue(intersection);
    })
}
