window.resetChanged = function()
{
    $('#pivotState').attr('data-changed', JSON.stringify([]));
}

window.getStateChange = function()
{
    const ignores = ['queryCols', 'queryData', 'pivotCols', 'pivotData', 'pivotCellSpan', 'default[]'];
    const data = {};
    let changed = $('#pivotState').data('changed');
    changed = changed.filter(key => !ignores.includes(key));
    changed.forEach(key => (data[key] = pivotState()[key]));

    return data;
}

window.pivotState = function(key, value)
{
    const state = $('#pivotState').data('state');
    let changed = $('#pivotState').data('changed');

    if(key?.length && state.hasOwnProperty(key))
    {
        state[key] = value;
        changed.push(key);
        changed = Array.from(new Set(changed));
        $('#pivotState').attr('data-state', JSON.stringify(state));
        $('#pivotState').attr('data-changed', JSON.stringify(changed));
    }

    return state;
}

window.postQueryResult = function(e, info)
{
    const { item } = info;
    const { page } = item;
    const { pager } = pivotState();
    const { pageID, pageTotal } = pager;

    if(page == 'first' && pageID != 1)            pager.pageID = 1;
    if(page == 'last' && pageTotal != pageID)     pager.pageID = pageTotal;
    if(page == 'prev' && pageID - 1 >= 1)         pager.pageID = pageID - 1;
    if(page == 'next' && pageID + 1 <= pageTotal) pager.pageID = pageID + 1;

    pivotState('pager', pager);
    updateDesignPage('query');
}

window.updateDesignPage = function(action, index)
{
    const {link, formData, selectors} = buildPostParams(action, index);

    postAndLoadPage(link, formData, selectors, {modal: true});
}

window.postDesignPage = function(action, index)
{
    const {link, formData} = buildPostParams(action, index);
    postAndLoadPage(link, formData, `pageJS/.zin-page-js,#configJS`, {modal: true});
}

window.buildPostParams = function(action, index)
{
    if(!action) action = pivotState.action;
    pivotState.action = action;

    let actionSelectors = actionLoadTarget[action] ?? '#stepContent';
    if(Number.isInteger(index) || index) {
        let regex = new RegExp('%s', "g");
        actionSelectors = actionSelectors.replace(regex, index);
    }

    const data = getStateChange();
    if(action.startsWith('sqlBuilder-'))
    {
        const builder = getSqlBuilderPost(action, index);
        data.sqlbuilder = builder.data;
        actionSelectors = builder.selectors;
        if(action != 'sqlBuilder-step')
        {
            pivotState('sqlChanged', true);
            data.sqlChanged = true;
            disableNextStep();
        }
    }

    const formData  = zui.createFormData({action, data: JSON.stringify(data)});
    let selectors = [actionSelectors, '#dictionarySideBar', '#pivotState', '#sqlModal', '#saveAsDraft', '#stepNav', 'pageJS/.zin-page-js', 'pageCSS/.zin-page-css>*', '#configJS'];
    const link = $('#pivotState').data('url');

    return {link, formData, selectors: selectors.filter(selector => selector?.length != 0).join(',')};
}

window.allowAutoGenDrills = function()
{
    pivotState('autoGenDrills', true);
}

window.setDrillType = function(modalID, type)
{
  setDrillField(modalID, 'type', type);
}

window.setDesignChangedWarning = function(targetField = 'all', showWarning = true)
{
    const {drills} = pivotState();
    drills.forEach(function(value, index, drills)
    {
        if(!drills[index]) return;
        if(targetField == 'all' || targetField == value.field || value.field == '' || !value.field) drills[index]['warning'] = showWarning;
    });
    pivotState('drills', drills);
}

window.saveSettings = function()
{
    pivotState('checkStepDesign', true);
    updateDesignPage('table');
}

window.savePivot = function(saveStage = 'publish')
{
    const {stage, used} = pivotState();
    let isPublished = stage == 'published';
    if(saveStage == 'draft')
    {
        if(!canSwitchStepOrSave()) return zui.Modal.alert({message: cannotNextStep});
        return isSqlChanged() ? clearAllConfirm(() => setTimeout(() => confirmSaveDraft(isPublished),200)) : confirmSaveDraft(isPublished);
    }

    if(used)
    {
        zui.Modal.confirm({message: confirmPublish}).then((res) =>
        {
            if(res) save(saveStage);
        });
    }
    else
    {
        save(saveStage);
    }
}

window.save = function(stage)
{
    pivotState('stage', stage);
    const data = getStateChange();
    const formData = zui.createFormData(data ? {action: 'publish', data: JSON.stringify(data)} : {action: 'publish'});
    const link = $('#pivotState').data('url');

    $.post(link, formData, function(response)
    {
        response = JSON.parse(response);
        if(response.result == 'success')
        {
            zui.Messager.show({content: response.message, type: 'success', time: 1000});
            setTimeout(() => loadPage(response.locate), 1000);
        }
    });
}

/**
 * 查询条件改变时重新加载自定义透视表。
 * Reload custom pivot table when query conditions changed.
 *
 * @access public
 * @return void
 */
window.loadCustomPivot = function()
{
    updateDesignPage('table');
}

/**
 * Judge whether filter is query type.
 *
 * @access public
 * @return bool
 */
window.isQueryFilter = function()
{
    const {filters} = pivotState();
    return filters.some(function(filter) {
        return filter.from === 'query';
    });
}

window.handleFilterChange = function(event, name, suffix)
{
    const $target = $(event?.target);
    if(!$target) return;

    const value     = $target.val();
    let valueKey    = '';

    if(suffix) valueKey = suffix

    let {pivotFilters} = pivotState();
    pivotFilters = pivotFilters.map((filters) => {
        return filters.map((filter) => {
            if(filter.name == name)
            {
                filter.value = valueKey.length ? {...filter.value, [valueKey]: value} : value;
            }
            return filter;
        });
    });

    pivotState('pivotFilters', pivotFilters);
}

window.ajaxQuery = function()
{
    updateDesignPage('query');
}

window.changeStep = function(e)
{
    const $a = $(e.target).closest('a');
    const step = $a.attr('step');
    if(step == pivotState().step) return;

    if(!canSwitchStepOrSave()) return zui.Modal.alert({message: cannotNextStep});
    isSqlChanged() ? clearAllConfirm(() => switchStep(step)) : switchStep(step);
}

window.canSwitchStepOrSave = function()
{
    return pivotState().step != 'query' || $('.query-next-disabled').hasClass('hidden');
}

window.isSqlChanged = function()
{
    if(!pivotState().sqlChanged) return false;
    return checkSqlChange();
}

window.checkSqlChange = function()
{
    const fields = pivotState().fieldSettings;

    const settings = pivotState().settings;
    for(let key in settings)
    {
        if(key.startsWith('group') && settings[key]?.length && !fields.hasOwnProperty(settings[key])) return true;
    }

    if(settings.columns)
    {
        for(let column of settings.columns)
        {
            if(!fields.hasOwnProperty(column.field) || (column.slice != 'noSlice' && !fields.hasOwnProperty(column.slice))) return true;
        }
    }

    if(!isQueryFilter())
    {
        const filters = pivotState().filters;
        for(let filter of filters)
        {
            if(!fields.hasOwnProperty(filter.field)) return true;
        }
    }

    const drills = pivotState().drills;
    for(let drill of drills)
    {
        if(drill === null) continue;

        if(!fields.hasOwnProperty(drill.field)) return true;
        for(let condition of drill.condition)
        {
            if(!fields.hasOwnProperty(condition.queryField)) return true;
        }
    }

    return false;
}

window.removeInvalidProps = function()
{
    const fields = pivotState().fieldSettings;

    const settings = pivotState().settings;
    if(settings.columns) settings.columns = settings.columns.filter((column) => fields.hasOwnProperty(column.field) && (column.slice == 'noSlice' || fields.hasOwnProperty(column.slice)));
    const groups = [];
    Object.keys(settings).forEach(key => {
        if(key.startsWith('group'))
        {
            value = settings[key];
            if(fields.hasOwnProperty(value)) groups.push(value);
            delete settings[key];
        }
    });
    groups.forEach((group, index) => {
        settings['group' + (index + 1)] = group;
    });
    pivotState('settings', settings);

    if(!isQueryFilter())
    {
        const filters = pivotState().filters;
        let pivotFilters = pivotState().pivotFilters;
        const indexes = filters.reduce((indexes, filter, index) => {
            if(!fields.hasOwnProperty(filter.field)) indexes.push(index);
            return indexes;
        }, []);
        pivotState('filters', filters.filter((_, index) => !indexes.includes(index)));
        pivotFilters[1] = pivotFilters[1].filter((_, index) => !indexes.includes(index));
        pivotState('pivotFilters', pivotFilters);
    }

    let drills = pivotState().drills;
    drills = drills.filter((drill) => fields.hasOwnProperty(drill.field));
    drills.forEach((drill, index) => {
        drill.condition = drill.condition.filter((condition) => fields.hasOwnProperty(condition.queryField));
        drills[index] = drill;
    });
    pivotState('drills', drills);
}

window.clearSettings = function()
{
    pivotState('settings', []);
    if(!isQueryFilter())
    {
        pivotState('filters', []);
        pivotState('pivotFilters', []);
    }
    pivotState('drills', []);
    pivotState('step2FinishSql', '');
}

window.clearAllConfirm = function(afterConfirm = null)
{
    const clearBtn = {key: 'clear', type: 'primary', text: clearLang, 'class': 'btn btn-wide'};
    const keepBtn = {key: 'keep', type: 'default', text: keepLang, 'class': 'btn btn-wide'};
    const confirmOptions = {
        message: resetSettingsTip,
        actions: [clearBtn, keepBtn],
        onClickAction: (item) => {
            pivotState('sqlChanged', false);
            item.key == 'clear' ? clearSettings() : removeInvalidProps();
            afterConfirm?.();
        }
    };
    zui.Modal.confirm(confirmOptions);
}

window.confirmSaveDraft = function(isPublished)
{
    if(pivotState().used)
    {
        zui.Modal.confirm({message: confirmDraft}).then((res) =>
        {
            if(res) save('draft');
        });
    }
    else
    {
        if(isPublished)
        {
            zui.Modal.confirm({message: draftSave}).then((res) =>
            {
                if(res) save('draft');
            });
        }
        else
        {
            save('draft');
        }
    }
}

window.setChanged = function(changed = true)
{
    pivotState('filterChanged', changed);
}

window.getChanged = function()
{
    return pivotState().changedWithoutSave;
}

window.nextStep = function()
{
    const currentStep = pivotState().step;
    const index       = stepOrder.findIndex(s => s == currentStep);
    if(index + 1 >= stepOrder.length) return;
    const nextStep    = stepOrder[index + 1];

    if(!canSwitchStepOrSave()) return zui.Modal.alert({message: cannotNextStep});
    isSqlChanged() ? clearAllConfirm(() => switchStep(nextStep)) : switchStep(nextStep);
}

window.switchStep = function(step)
{
    const designActions = $('#pivotState').data('actions');
    const action = designActions[step][0];

    pivotState('step', step);
    if(pivotState().mode == 'builder')
    {
        const {sqlbuilder} = pivotState();
        sqlbuilder.step = 'table';
        if(sqlbuilder.sql !== undefined) sqlbuilder.sql = window.latin1ToBase64(sqlbuilder.sql);
        pivotState('sqlbuilder', sqlbuilder);
    }
    updateDesignPage(action);
}

window.handleSqlChange = function()
{
    const sql = window.latin1ToBase64($('#sqlForm').find('textarea[name="sql"]').val());
    pivotState('sql', sql);
    pivotState('sqlChanged', true);
    if(pivotState().mode == 'text')
    {
        const canChangeMode = !sql?.trim()?.length;
        pivotState('canChangeMode', canChangeMode);
        canChangeMode ? $('#changeMode').removeClass('hidden')      : $('#changeMode').addClass('hidden')
        canChangeMode ? $('#changeModeDisabled').addClass('hidden') : $('#changeModeDisabled').removeClass('hidden')
    }

    disableNextStep();
}

window.disableNextStep = function()
{
    $('.query-next').addClass('hidden');
    $('.query-next-disabled').removeClass('hidden');
}

window.saveFields = function()
{
    let $form    = $('#fieldSettingsForm');
    let formData = new FormData($form[0]);
    let data = {};
    for (var [key, value] of formData.entries()) {
        const pureKey = key.substring(0, key.indexOf("["));
        if(!data[pureKey]) data[pureKey] = [];
        data[pureKey].push(value);
    }

    let fieldSettings = {};
    data.key.forEach((key, index) => {
        fieldSettings[key] = {};
        Object.keys(data).forEach(itemKey => {
            if(itemKey == 'key') return;
            fieldSettings[key][itemKey] = data[itemKey][index];
        });
    });

    pivotState('fieldSettings', fieldSettings);

    updateDesignPage('saveFields');
}

window.exportData = function()
{
    var $domObj = $('#stepContent').find(".table-condensed")[0];

    exportFile($domObj);
}

window.renderDrillResult = function(result, {col, row})
{
    if(col.name == 'name' && row.data.type == 'program') result[0].props.href = $.createLink('program', 'kanban');

    return result;
}
