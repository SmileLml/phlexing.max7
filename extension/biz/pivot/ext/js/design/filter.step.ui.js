window.addFilter = function()
{
    if(isQueryFilter()) return;
    allowAutoGenDrills();
    setDesignChangedWarning();

    setChanged();
    updateDesignPage('addFilter');
}

window.removeFilter = function(event)
{
    const {index} = getFilter(event);
    let {filters} = pivotState();
    filters.splice(index, 1);
    allowAutoGenDrills();
    setDesignChangedWarning();

    setChanged();
    pivotState('filters', filters);
    updateDesignPage('removeFilters');
}

window.saveFilters = function(event)
{
    updateDesignPage('table');
}

window.changeFilterDefault = function(event)
{
    const {index} = getFilter(event);
    let {filters} = pivotState();
    const filter  = filters[index];
    const type    = filter.type;
    const $target = $(event.target);

    let value = filter.default;
    if(filter?.from != 'query' && (type === 'date' || type == 'datetime'))
    {
        const key  = $target.attr('name').split('_')[1];
        value[key] = $target.val();
    }
    else
    {
        value = $target.val();
    }
    filters[index].default = value;
    setChanged();
    pivotState('filters', filters);
}

window.changeFilter = function(event, $key)
{
    const {index, value, name} = getControlValue(event);
    let {filters} = pivotState();
    filters[index][$key] = value;

    if($key == 'field') filters[index].name = name;

    if(['type', 'field'].includes($key))
    {
        delete filters[index].default;

        allowAutoGenDrills();
        setDesignChangedWarning();
    }

    setChanged();
    pivotState('filters', filters);
    if(['type', 'field', 'saveAs'].includes($key)) updateDesignPage('filters', index);
}

window.getControlValue = function(event)
{
    const $target = $(event.target);
    const $picker = $target.zui('picker');
    const {index} = getFilter(event);
    return {
        index,
        value: $target.val(),
        name: $picker?.$?.state?.selections?.[0]?.text
    };
}

window.getFilter = function(event)
{
    const $target = $(event.target);
    return {
        index: $target.closest('.filter-form').data('index')
    };
}
