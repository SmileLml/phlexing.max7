window.deleteDrill = function(event)
{
    let {drills} = pivotState();
    let drillIndex = getDrillIndex(event);
    delete drills[drillIndex];

    pivotState('drills', drills);
    updateDesignPage('deleteDrill');
}

/* Drill setting */
window.getDrillIndex = function(e)
{
    return parseInt($(e.target).closest(".drill-line").data('index'));
}

window.addCondition = function(event)
{
    let index        = getConditionIndex(event);
    let modalID      = getModalID(event);
    let newCondition = {'drillObject': '', 'drillAlias': '', 'drillField': '', 'queryField': ''};
    setDrillType(modalID, 'manual');

    let {drills, defaultDrill} = pivotState();

    if(modalID == 'drillModalDefault') {
        defaultDrill.condition.splice(index, 0, newCondition);
        pivotState('defaultDrill', defaultDrill);
        updateDesignPage('addCondition', 'Default');
    }
    else {
        let drillIndex = getModalDrillIndex(modalID);
        drills[drillIndex].condition.splice(index, 0, newCondition);
        pivotState('drills', drills);
        updateDesignPage('addCondition', drillIndex);
    }
}

window.deleteCondition = function(event)
{
    let index   = getConditionIndex(event) - 1;
    let modalID = getModalID(event);
    setDrillType(modalID, 'manual');

    let {drills, defaultDrill} = pivotState();

    if(modalID == 'drillModalDefault') {
        defaultDrill.condition.splice(index, 1);
        pivotState('defaultDrill', defaultDrill);
        updateDesignPage('addCondition', 'Default');
    }
    else {
        let drillIndex = getModalDrillIndex(modalID);
        drills[drillIndex].condition.splice(index, 1);
        pivotState('drills', drills);
        updateDesignPage('addCondition', drillIndex);
    }
}

window.changeField = function(event)
{
    setDrillField(getModalID(event), 'field', $(event.target).val());
    setDrillType(getModalID(event), 'manual');
}

window.changeWhereSQL = function(event)
{
    setDrillField(getModalID(event), 'whereSql', $(event.target).val());
    setDrillType(getModalID(event), 'manual');
}

window.changeDrillField = function(event)
{
    let index = getConditionIndex(event) - 1;

    const value = $(event.target).val();
    let drillFieldKeys = value.split('.');
    let isSubquery = drillFieldKeys.length == 2;

    let drillObject = isSubquery ? '' : drillFieldKeys[1];
    let drillAlias  = drillFieldKeys[0];
    let drillField  = isSubquery ? drillFieldKeys[1] : drillFieldKeys[2];

    setDrillField(getModalID(event), `condition.${index}.drillObject`, drillObject);
    setDrillField(getModalID(event), `condition.${index}.drillAlias`, drillAlias);
    setDrillField(getModalID(event), `condition.${index}.drillField`, drillField);
    setDrillType(getModalID(event), 'manual');
}

window.changeQueryField = function(event)
{
    let index = getConditionIndex(event) - 1;
    setDrillField(getModalID(event), `condition.${index}.queryField`, $(event.target).val());
    setDrillType(getModalID(event), 'manual');
}

window.changeObject = function(event)
{
    const modalID = getModalID(event);
    setDrillField(modalID, 'object', $(event.target).val());
    setDrillType(modalID, 'manual');

    let {drills, defaultDrill} = pivotState();

    if(modalID == 'drillModalDefault') {
        defaultDrill.objectChanged = 1;
        pivotState('defaultDrill', defaultDrill);
        updateDesignPage('changeObject', 'Default');
    }
    else {
        let drillIndex = getModalDrillIndex(modalID);
        drills[drillIndex].objectChanged = 1;
        pivotState('drills', drills);
        updateDesignPage('changeObject', drillIndex);
    }
}

window.checkRequiredFields = function(modalID)
{
    let hasError = false;
    $(`#${modalID} .form-tip`).addClass('hidden');

    let drill = modalID == 'drillModalDefault' ? pivotState().defaultDrill : pivotState().drills[getModalDrillIndex(modalID)];

    if(drill.field == '') {
        hasError = true;
        showErrorTip(modalID, 'field');
    }
    if(drill.object == '') {
        hasError = true;
        showErrorTip(modalID, 'object');
    }
    drill.condition.forEach((condition, index) => {
        if(condition.drillField == '') {
            hasError = true;
            showErrorTip(modalID, 'drillField', index + 1);
        }
        if(condition.queryField == '') {
            hasError = true;
            showErrorTip(modalID, 'queryField', index + 1);
        }
    });
    return hasError;
}

window.showErrorTip = function(modalID, field, index = null)
{
    let divClass = `.${field}-select`;
    if(index) divClass = `.${field}-select-${index}`;

    let selector = `#${modalID} ${divClass}`;
    $(selector).find('.form-tip').removeClass('hidden');
}

window.getModalID = function(event)
{
    return $(event.target).closest('.modal').attr('id');
}

window.getModalDrillIndex = function(modalID)
{
    if(modalID == 'drillModalDefault') return 'Default';
    return parseInt(modalID.substring(10));
}

window.getConditionIndex = function(event)
{
    return $(event.target).closest('.condition-line').data('index');
}

window.setDrillField = function(modalID, str, value)
{
    let parts = str.split(".");
    if(parts.length > 3) return;

    let {drills, defaultDrill} = pivotState();

    if(modalID == 'drillModalDefault')
    {
        if(parts.length == 1)
        {
            let field = parts[0];
            defaultDrill[field] = value;
        }
        else if(parts.length == 3)
        {
            let field = parts[0];
            let index = parts[1];
            let key   = parts[2];
            defaultDrill[field][index][key] = value;
        }
        pivotState('defaultDrill', defaultDrill);
    }
    else
    {
        let drillIndex = getModalDrillIndex(modalID);
        if(parts.length == 1)
        {
            let field = parts[0];
            drills[drillIndex][field] = value;
        }
        else if(parts.length == 3)
        {
            let field = parts[0];
            let index = parts[1];
            let key   = parts[2];
            drills[drillIndex][field][index][key] = value;
        }
        pivotState('drills', drills);
    }
}

window.previewDrillResult = function(event)
{
    let {drills, defaultDrill} = pivotState();
    let modalID    = getModalID(event);
    let drillIndex = getModalDrillIndex(modalID);
    let whereSQL   = modalID == 'drillModalDefault' ? defaultDrill.whereSql : drills[getModalDrillIndex(modalID)].whereSql;
    let index      = modalID == 'drillModalDefault' ? 'Default' : drillIndex;

    if(modalID == 'drillModalDefault')
    {
        defaultDrill.preview = 1;
        pivotState('defaultDrill', defaultDrill);
    }
    else
    {
        drills[drillIndex].preview = 1;
        pivotState('drills', drills);
    }

    if(checkRequiredFields(modalID)) return;
    updateDesignPage('previewResult', index);
}

window.dryRunDrillSQL = function(modalID, postData, callback)
{
    let errorDiv = $('#' + modalID).find('.error-message');
    errorDiv.empty();

    let url = $.createLink('pivot', 'ajaxGetPreviewResult');
    $.post(url, postData, function(result)
        {
            let resp = JSON.parse(result);

            if(resp.status == 'success') callback();
            if(resp.status == 'fail')
            {
                let div = "<div class='form-tip text-danger'>" + resp.error + "</div>";
                errorDiv.append(div);
            }
        });
}

window.saveDrill = function(event)
{
    let modalID = getModalID(event);
    if(checkRequiredFields(modalID)) return;

    let {drills, defaultDrill, filters} = pivotState();
    let drillIndex = getModalDrillIndex(modalID);
    let drill      = modalID == 'drillModalDefault' ? defaultDrill : drills[drillIndex];

    let postData = {'object': drill.object, 'whereSql': drill.whereSql, 'filters': JSON.stringify(filters)};
    dryRunDrillSQL(modalID, postData, function()
    {
        if(modalID == 'drillModalDefault') drills.push(defaultDrill);
        if(modalID != 'drillModalDefault') setDesignChangedWarning(drill.field, false);
        pivotState('drills', drills);
        updateDesignPage(modalID == 'drillModalDefault' ? 'addDrill' : 'editDrill');
    });
}

window.refreshConditions = function(event)
{
    let {drills, defaultDrill, filters} = pivotState();
    let modalID    = getModalID(event);
    let drillIndex = getModalDrillIndex(modalID);
    let drill      = modalID == 'drillModalDefault' ? defaultDrill : drills[drillIndex];

    let postData = {'object': drill.object, 'whereSql': drill.whereSql, 'filters': JSON.stringify(filters)};
    dryRunDrillSQL(modalID, postData, function()
    {
        updateDesignPage('addCondition', drillIndex);
    });
}
