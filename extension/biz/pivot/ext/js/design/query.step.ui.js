window.handleQueryFilterChange = function(event)
{
    const {index, mode, name, value} = getQueryFilter(event);
    let {filters, addQueryFilter} = pivotState();

    if(mode == 'edit')
    {
        filters[index][name] = value;
        pivotState('filters', filters);
    }
    else
    {
        addQueryFilter[name] = value;
        pivotState('addQueryFilter', addQueryFilter);
    }
    if(['type', 'typeOption'].includes(name)) updateDesignPage('queryFilter');
}

window.changeMode = function(event)
{
    const mode = $(event.currentTarget).data('mode');

    if(mode == 'builder')
    {
        zui.Modal.confirm({message: changeModeTip}).then((res) =>
        {
            if(res) doChangeMode(mode);
        });
        return;
    }

    doChangeMode(mode);
}

window.doChangeMode = function(mode)
{
    const changeMode = mode == 'text' ? 'builder' : 'text';
    pivotState('mode', changeMode);
    pivotState('errorMsg', '');

    updateDesignPage('changeMode');
}

window.addQueryFilter = function()
{
    updateDesignPage('addQueryFilter');
}

window.deleteQueryFilter = function(event)
{
    const {index, mode} = getQueryFilter(event);
    let {filters, addQueryFilter} = pivotState();

    if(mode == 'edit')
    {
        filters.splice(index, 1);
        pivotState('filters', filters);
    }
    else
    {
        addQueryFilter = {};
        pivotState('addQueryFilter', addQueryFilter);
    }
    updateDesignPage('queryFilter');
}

window.saveQueryFilter = function()
{
    if(checkQueryFilter()) return;
    updateDesignPage('saveQueryFilter');
}

window.checkQueryFilter = function()
{
    $('#queryFilterPanel .form-tip').addClass('hidden');

    let hasError = false;
    const {filters, addQueryFilter} = pivotState();

    filters.push(addQueryFilter);

    filters.forEach((filter, index) =>
    {
        Object.keys(filter).forEach(name =>
        {
            if(!['field', 'name'].includes(name)) return;

            if(!filter[name]?.length)
            {
                hasError = true;
                showErrorTip(index, name);
            }
        });
    });

    return hasError;
}

window.showErrorTip = function(index, name)
{
    const formRow  = $('#queryFilterPanel .query-filter-row')[index];
    const $control = $(formRow).find('#' + name + 'Box').find('#' + name);
    const $formTip = $control.siblings('.form-tip');
    $formTip.removeClass('hidden');
}

window.getQueryFilter = function(event)
{
    const $target = $(event.target);
    const $row    = $target.closest('.query-filter-row');
    const index   = $row.data('index');
    const mode    = $row.data('mode');
    let name      = $target?.attr?.('name')?.split?.('_')?.[0];
    if(name && name.indexOf("[") !== -1) name = name.substring(0, name.indexOf("["));

    return {
        index,
        mode,
        name,
        value: $target?.val?.()
    }
}
