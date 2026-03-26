$(document).ready(function()
{
    var pivot = DataStorage.pivot;
    if(!pivot.settings) pivot.settings = {columns: [{field: '', stat: '', slice: 'noSlice', showMode: 'default', showTotal: 'noShow'}], summary: true};
    if(!pivot.filters || !(pivot.filters instanceof Array)) pivot.filters = [];
    DataStorage.pivot = pivot;

    switchStep(DataStorage.step);

    $('body').resize(() => {calcDesignGrowFilter();});
});

/**
 * Tab click callback.
 *
 * @param object evt
 * @param int    nextStep
 * @access public
 * @return void
 */
function tabClick(evt, nextStep)
{
    if($(evt).hasClass('disabled') || step == nextStep) return;

    var step = DataStorage.clone('step');
    if(nextStep < step)
    {
        DataStorage.step = nextStep;
        switchStep(nextStep);
        return;
    }

    finishStep(step, nextStep, function(nextStep)
    {
        DataStorage.step = nextStep;
        switchStep(nextStep);
    });
}

/**
 * Next step click callback.
 *
 * @access public
 * @return void
 */
function nextStep()
{
    var step = DataStorage.clone('step');
    finishStep(step, step + 1, function(nextStep)
    {
        DataStorage.step = nextStep;
        switchStep(nextStep);
    });
}

/**
 * Submit publish.
 *
 * @access public
 * @return void
 */
function publish(stage = 'published')
{
    var pivot       = DataStorage.pivot;
    var savedata    = JSON.parse(JSON.stringify(pivot));
    var isPublished = savedata.stage == 'published';
    savedata.stage  = stage;

    if(stage == 'published' && !validate()) return;
    if(stage == 'draft')
    {
        var newSql      = $('#sql').val().trim();
        var hasSettings = checkSettings();
        var sqlChange   = savedata.sql.length > 0 && newSql !== savedata.sql;

        if(sqlChange && hasSettings)
        {
            bootbox.confirm(clearSettingsLang, function(res)
            {
                if(res)
                {
                    /* Init pivot design settings */
                    savedata.settings = {columns: [{field: '', stat: '', slice: 'noSlice', showMode: 'default', showTotal: 'noShow'}]};
                    /* Init filter settings */
                    savedata.filters = savedata.filters.filter(function(filter){return filter.from == 'query';});

                    if(pivot.used)
                    {
                        bootbox.confirm({message:confirmDraft,callback:save.bind(null, savedata)});
                    }
                    else
                    {
                        if(isPublished) bootbox.confirm({message:pivotLang.draftSave,callback:save.bind(null, savedata)});
                        if(!isPublished) save(savedata);
                    }
                }
            });
        }
        else
        {
            if(pivot.used)
            {
                bootbox.confirm({message:confirmDraft,callback:save.bind(null, savedata)});
            }
            else
            {
                if(isPublished) bootbox.confirm({message:pivotLang.draftSave,callback:save.bind(null, savedata)});
                if(!isPublished) save(savedata);
            }
        }

        return;
    }

    if(pivot.used)  bootbox.confirm({message:confirmPublish,callback:save.bind(null, savedata)});
    if(!pivot.used) save(savedata);
}

function save(savedata, stus = true)
{
    if(!stus || DataStorage.saving) return;
    DataStorage.saving = true;
    savedata.sql               = $('#step1Content #sql').val().trim();
    savedata.settings.lastStep = DataStorage.step;
    savedata.fields            = Object.keys(DataStorage.fields);
    savedata.fieldSettings     = DataStorage.fieldSettings;
    savedata.langs             = DataStorage.langs;

    $.post(createLink('pivot', 'design', 'pivotID=' + savedata.id + '&from=' + from + '&params=' + params), savedata, function(response)
    {
        response = JSON.parse(response);

        if(response.result == 'success')
        {
            zuiMessage(response.result, response.message);
            DataStorage.pivot = savedata;
            setTimeout(function()
            {
                window.location.href = response.locate;
                DataStorage.saving = false;
            }, 2000);
        }
        else
        {
            DataStorage.saving = false;
            /* The result.message is just a string. */
            if($.type(response.message) == 'string')
            {
                if($('#responser').length == 0) return alert(response.message);
                return $('#responser').html(response.message).addClass('text-error').show().delay(3000).fadeOut(100);
            }

            /* The result.message is just a object. */
            if($.type(response.message) == 'object')
            {
                var triggered = new Array();
                $.each(response.message, function(key, value)
                {
                    /* Define the id of the error objecjt and it's label. */
                    var errorOBJ   = '#legendBasicInfo #' + key;
                    var errorLabel = key + 'Label';
                    var i          = triggered.push(false);

                    /* Create the error message. */
                    var errorMSG = '<div id="'  + errorLabel + '" class="text-danger red">';
                    errorMSG += $.type(value) == 'string' ? value : value.join(';');
                    errorMSG += '</div>';

                    /* Append error message, set style and set the focus events. */
                    $('#' + errorLabel).remove();
                    var isInputGroup = $(errorOBJ).closest('.input-group').size();
                    var $showOBJ     = isInputGroup ? $(errorOBJ).closest('.input-group').parent() : $(errorOBJ).parent();
                    $showOBJ.append(errorMSG);
                    $(errorOBJ).css('margin-bottom', 0);
                    $(errorOBJ).css('border-color','#D2322D');

                    if($(errorOBJ + '_chosen').size() > 0)
                    {
                        $(errorOBJ + '_chosen .chosen-single').css('border-color','#D2322D');
                        $(errorOBJ + '_chosen').bind('keydown mousedown', function()
                        {
                            if(!triggered[i])
                            {
                                $(this).removeAttr('style');
                                $('#' + errorLabel).remove();
                                triggered[i] = true;
                            }
                        });
                    }

                    $(errorOBJ).bind('keydown mousedown', function()
                    {
                        if(!triggered[i])
                        {
                            $(this).removeAttr('style');
                            $('#' + errorLabel).remove();
                            triggered[i] = true;
                        }
                    });

                    if(i == 1)$(errorOBJ).focus();
                })
            }
        }
    });
}

/**
 * Finish current step and check.
 *
 * @param int step
 * @access public
 * @return void
 */
function finishStep(step, nextStep, callback)
{
    if(step == 1)
    {
        var check = checkStep1();
        if(!check) return false;
        if(methods.step1.query.needEditQuery()) return false;

        var pivot = DataStorage.clone('pivot');

        /* Update sql query values */
        getFieldSettings();
        var newSql      = $('#sql').val().trim();
        /* Fix bug #32348. */
        newSql          = newSql.replace(';', '');
        pivot.sql       = pivot.sql.replace(';', '');

        var hasSettings = checkSettings();

        newSqlNoBr = newSql.replace(/\r\n/g, ' ');
        newSqlNoBr = newSqlNoBr.replace(/\n/g, ' ');
        oldSqlNoBr = pivot.sql.replace(/\r\n/g, ' ');
        oldSqlNoBr = oldSqlNoBr.replace(/\n/g, ' ');

        pivot.sqlChange = oldSqlNoBr.length > 0 && newSqlNoBr !== oldSqlNoBr;

        if(pivot.sqlChange && hasSettings)
        {
            bootbox.confirm(resetSettingsLang, function(res)
            {
                if(res)
                {
                    query(function()
                    {
                        /* Remove step active. */
                        $('#step1').nextAll('.step-tab').removeClass('active');
                        /* Set pivot refreshTab for function getFinish. */
                        pivot.refreshTab = true;
                        pivot.stage      = 'draft';

                        /* If filterType is not query Init filter settings */
                        if(!DataStorage.isQueryFilter()) pivot.filters = [];

                        /* Init pivot design settings */
                        pivot.settings = {columns: [{field: '', stat: '', slice: 'noSlice', showMode: 'default', showTotal: 'noShow'}], filterType: pivot.settings.filterType, columnTotal: 'noShow'};

                        /* Remove reportData table and show empty tip. */
                        $('#datagridSpanExample2').find('.reportData').remove();
                        $('#datagrid-tip').removeClass('hidden');

                        pivot.sql           = newSql;
                        pivot.fields        = Object.keys(DataStorage.fields);
                        pivot.fieldSettings = DataStorage.fieldSettings;

                        DataStorage.pivot = pivot;
                        if(callback instanceof Function) callback(2);
                    });
                }
            });
            return false;
        }

        pivot.sql           = newSql;
        pivot.fields        = Object.keys(DataStorage.fields);
        pivot.fieldSettings = DataStorage.fieldSettings;
        DataStorage.pivot = pivot;
    }

    if(step == 2)
    {
        var check = checkStep2();
        if(!check) return false;
    }

    if(callback instanceof Function) callback(nextStep);
}

/**
 * Get current finish step stage.
 *
 a @access public
 * @return int
 */
function getFinish()
{
    var pivot = DataStorage.clone('pivot');
    if('refreshTab' in pivot)
    {
        delete pivot.refreshTab;
        DataStorage.pivot = pivot;
        return 1;
    }
    if(pivot.stage == 'published' || pivot.stage == 4) return 4;

    if(checkStep2(false)) return 2;

    if(checkStep1(false)) return 1;

    return 0;
}

function checkSettings()
{
    var pivot = DataStorage.pivot;
    for(var key in pivot.settings)
    {
        if(key.indexOf('group') != '-1' && pivot.settings[key].length > 0) return true;
        if(pivot.settings[key].length > 0) return true;
        if(key == 'columns')
        {
            var columns = pivot.settings.columns;
            if(columns.length > 1) return true;
            if(columns.length == 1 && columns[0].field.length > 0) return true;
        }
    }

    return false;
}

/**
 * Check step 1 is finished.
 *
 * @param bool page
 * @access public
 * @return bool
 */
function checkStep1(page = true)
{
    var pivot = DataStorage.pivot;
    if(!page)
    {
        var sql           = pivot.sql;
        var fields        = DataStorage.fields;
        var fieldSettings = DataStorage.fieldSettings;

        return sql && fieldSettings && fields && sql.length > 0 && Object.keys(fieldSettings).length > 0 && Object.keys(fields).length > 0;
    }
    $.ajaxSettings.async = false;
    query();
    $.ajaxSettings.async = true;
    return $('#dataform .error').length == $('#dataform .error.hidden').length;
}

/**
 * Check step 2 is finished.
 *
 * @param bool page
 * @access public
 * @return bool
 */
function checkStep2(page = true)
{
    var pivot = DataStorage.pivot;
    if(!page)
    {
        var settings = pivot.settings;
        return settings && validate();
    }
    return validate(true);
}

function switchStep(step)
{
    setStepActive(step);
    setStepEnable(step);

    var pivot = DataStorage.pivot;

    if(step == 1)
    {
        filters = pivot.filters;
        if(filters.length > 0 && filters[0].from != 'query')
        {
            $('#step1Content .add-query-filter').addClass('disabled').attr('disabled', true).attr('title', cannotAddQuery);
        }
        else
        {
            $('#step1Content .add-query-filter').removeClass('disabled').attr('disabled', false).removeAttr('title');
        }
        methods.step1.query.renderFilters();
        return;
    }

    if(step == 2 && Object.keys(DataStorage.fields).length > 0)
    {
        $.post(createLink('pivot', 'ajaxGetFieldSelect'),
        {
            fields: DataStorage.fieldSettings,
            langs: DataStorage.langs
        }, function(data)
        {
            $('#step2Content #fieldSelectTpl').html(data);
            initStep2();
        });
    }

    if(step != 1) apply(false);
    if(step == 2) initSummary();

    if(step == 2 || step == 3 || step == 4) renderFilters(step);
}

function initStep2()
{
    initGroupForm();
    renderColumns();
}

/**
 * Set step tab label active.
 *
 * @param int step
 * @access public
 * @return void
 */
function setStepActive(step)
{
    var pivot    = DataStorage.pivot;
    var lastStep = pivot.settings.lastStep;
    /* Remove old class and Add new active class. */
    //$('.step-tab').removeClass('active current');
    $('.step-tab').removeClass('current');
    $('#step' + step).addClass('active current');
    $('#step' + step).prevAll('.step-tab').addClass('active');

    $('.step-content').hide();
    $('#step' + step + 'Content').show();
}

/**
 * Set step tab label enable.
 *
 * @access public
 * @return void
 */
function setStepEnable(step)
{
    var stus   = [];
    var finish = getFinish();

    if(finish == 0) stus = [1, 1, 0, 0];
    if(finish == 1 && step == 1) stus = [1, 1, 0, 0];
    if(finish == 1 && step == 2) stus = [1, 1, 1, 0];
    if(finish == 2 || finish == 4) stus = [1, 1, 1, 1];

    $('.step-tab').removeClass('disabled');
    $('.step-tab').removeClass('clickable');
    stus.forEach(function(value, step)
    {
        var disable = value == 1 ? '' : 'disabled';
        $('#step' + (step + 1)).addClass(disable);
        /* If step haven't active and disabled, means it hasn't be clicked to set active, but it's clickable now. */
        if(!$('#step' + (step + 1)).hasClass('active') && !$('#step' + (step + 1)).hasClass('disabled')) $('#step' + (step + 1)).addClass('clickable')
    });
}

function exportData()
{
    var step    = DataStorage.clone('step');
    var $domObj = $('#datagridSpanExample' + step).find(".table-condensed")[0];

    exportFile($domObj);
}

function initSummary()
{
    var pivot     = DataStorage.clone('pivot');
    var isChecked = true;
    if("summary" in pivot.settings && pivot.settings['summary'] == 'notuse') isChecked = false;

    if(isChecked) $("#summary").prop("checked", true);
    setSummaryForm(isChecked);
}

function changeSummary()
{
    var isChecked = $("#summary").prop("checked");

    var pivot = DataStorage.clone('pivot');
    pivot.settings['summary'] = isChecked ? 'use' : 'notuse';
    DataStorage.pivot = pivot;

    setSummaryForm(isChecked);
}

function setSummaryForm(isChecked)
{
    if(!isChecked)
    {
        $('#summaryForm').addClass('hidden');
        $('.btn-save-setting').addClass('hidden');
        apply();
    }
    else
    {
        $('#summaryForm').removeClass('hidden');
        $('.btn-save-setting').removeClass('hidden');
    }
}
