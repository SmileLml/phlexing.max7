/* Group */
window.getGroupValues = function(event)
{
    let groups = [];
    for(let key in pivotState().settings) {
        if(!key.startsWith("group")) continue;
        groups.push(pivotState().settings[key]);
    }

    return groups;
}

window.addSettingGroup = function(event)
{
    let groupIndex  = getGroupIndex(event);
    let groupValues = getGroupValues();

    groupValues.splice(groupIndex, 0, '');

    rebuildGroups(groupValues);
    allowAutoGenDrills();
    setDesignChangedWarning();
    updateDesignPage('settings');
}

window.deleteSettingGroup = function(event)
{
    let groupIndex  = getGroupIndex(event) - 1;
    let groupValues = getGroupValues();

    groupValues.splice(groupIndex, 1);

    rebuildGroups(groupValues);
    allowAutoGenDrills();
    setDesignChangedWarning();
    updateDesignPage('deleteGroup');
}

window.rebuildGroups = function(groupValues)
{
    let {settings} = pivotState();
    for(let key in settings) {
        if(key.startsWith("group")) delete settings[key];
    }

    let index = 1;
    for(let key in groupValues) {
        let group = 'group' + index;
        settings[group] = groupValues[key];

        index ++;
    }
    pivotState('settings', settings);
}

/* Column */
window.addSettingColumn = function(event)
{
    allowAutoGenDrills();
    updateDesignPage('addColumn');
}

window.deleteSettingColumn = function(event)
{
    let {settings} = pivotState();
    let columnIndex = getColumnIndex(event) - 1;
    let column      = settings.columns[columnIndex];
    settings.columns.splice(columnIndex, 1);
    allowAutoGenDrills();

    removeDrillByColumn(column.field);
    pivotState('settings', settings);
    updateDesignPage('deleteColumn');
}

/* change function */
window.changeSettingGroup = function(event)
{
    allowAutoGenDrills();
    let key = getGroupName(event);
    setDesignChangedWarning();
    setPivotState(key, $(event.target).val());
}

window.changeSettingColumnField = function(event)
{
    let columnField = $(event.target).closest('.column-line').find('.picker-column').find('.form-group-wrapper').zui('picker').$.value;
    changeColumnCommon(event, 'field', $(event.target).val());
    allowAutoGenDrills();
    setDesignChangedWarning(columnField, true);

    removeDrillFields();
}

window.changeSettingColumnOrigin = function(event)
{
    changeColumnCommon(event, 'showOrigin', $(event.target).is(':checked') ? 1 : 0)
    allowAutoGenDrills();
    updateDesignPage('changeOrigin', getColumnIndex(event));
}

window.changeSettingColumnSlice = function(event)
{
    let columnField = $(event.target).closest('.column-line').find('.picker-column').find('.form-group-wrapper').zui('picker').$.value;
    setDesignChangedWarning(columnField, true);

    changeColumnCommon(event, 'slice', $(event.target).val());
    allowAutoGenDrills();

    updateDesignPage('changeSlice', getColumnIndex(event));
}

window.changeSettingColumnStat = function(event)
{
    changeColumnCommon(event, 'stat', $(event.target).val());
}

window.removeDrillByColumn = function(field)
{
    if(!pivotState().drills.length) return;

    let {drills} = pivotState();
    const index = drills.findIndex(drill => drill.field == field);
    if(index < 0) return;

    const drill = drills[index];
    if(drill.type == 'auto')
    {
        delete drills[index];
        return;
    }
    drills[index].field = '';

    pivotState('drills', drills);
}

window.removeDrillFields = function()
{
    let {settings, drills, defaultDrill} = pivotState();
    let fields = settings.columns.map(column => column.field);

    if(!fields.includes(defaultDrill.field)) defaultDrill.field = '';
    if(drills.length)
    {
        for(drillIndex in drills)
        {
            let field = drills[drillIndex].field;
            if(!fields.includes(field)) drills[drillIndex].field = '';
        }
    }

    pivotState('drills', drills);
    pivotState('defaultDrill', defaultDrill);
}

window.changeSettingColumnShowMode = function(event)
{
    changeColumnCommon(event, 'showMode', $(event.target).val());
    updateDesignPage('changeShowMode', getColumnIndex(event));
}

window.changeSettingColumnMonopolize = function(event)
{
    let monopolize = $(event.target).is(':checked') ? 1 : 0;
    changeColumnCommon(event, 'monopolize', monopolize);
}

window.changeShowTotal = function(event)
{
    changeColumnCommon(event, 'showTotal', $(event.target).val());
}

window.handleSummaryChange = function(event)
{
    const checked = $(event.target).is(':checked');
    if(checked)
    {
        setPivotState('summary', 'use');
    }
    else
    {
        setPivotState('', {summary: 'notuse'});
    }
    allowAutoGenDrills();
    updateDesignPage('settings');
}

window.handleColumnTotalChange = function(event)
{
    setPivotState('columnTotal', $(event.target).val());
    updateDesignPage('summaryColumn');
}

window.handleColumnPositionChange = function(event)
{
    setPivotState('columnPosition', $(event.target).val());
}

/* Function changeColumnCommon */
window.changeColumnCommon = function(e, field, value)
{
    let columnIndex = getColumnIndex(e) - 1;
    let settingStr  = 'columns.' + columnIndex + '.' + field;

    setPivotState(settingStr, value);
}

/* Tool function, need not care about them at most of time. */
/* set pivotState field function */
window.commonPivotState = function(str, value = '', mode = 'set')
{
    let {settings} = pivotState();
    let parts = str.split(".");
    const length = parts.length;
    if(length > 3) return;

    let [field, index, key] = parts;
    if(mode == 'set')
    {
        if(length == 1) settings = field == '' ? value : {...settings, [field]: value};
        if(length == 2) settings[field][index] = value;
        if(length == 3) settings[field][index][key] = value;
        pivotState('settings', settings);
    }

    if(mode == 'get')
    {
        if(length == 1) return field == '' ? settings : settings[field];
        if(length == 2) return settings[field][index];
        if(length == 3) return settings[field][index][key];
    }
}

window.setPivotState = function(str, value)
{
    return commonPivotState(str, value, 'set');
}

window.getPivotState = function(str)
{
    return commonPivotState(str, '', 'get');
}

/* Group setting */
window.getGroupName = function(e)
{
    return $(e.target).closest(".group-line").data('key');
}

window.getGroupIndex = function(e)
{
    return parseInt(getGroupName(e).slice(5));
}

/* Column setting */
window.getColumnIndex = function(e)
{
    return parseInt($(e.target).closest(".column-line").data('index'));
}
