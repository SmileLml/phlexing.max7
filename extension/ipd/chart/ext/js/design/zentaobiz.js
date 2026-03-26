var echart;
var noDataDom;

$(function()
{
    var chart = DataStorage.clone('chart');
    if(!chart.settings || !(chart.settings instanceof Array)) chart.settings = [{type: chart.type ? chart.type : 'pie'}];
    if(!chart.settings.length) chart.settings = [{type: chart.type ? chart.type : 'pie'}];
    if(!chart.filters || !(chart.filters instanceof Array))   chart.filters  = [];
    DataStorage.chart = chart;

    switchStep(DataStorage.step);

    $('#type').picker({'maxDropHeight': pickerHeight});
    $('body').resize(() => {calcDesignGrowFilter(true);});
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
    var step = DataStorage.step;
    if($(evt).hasClass('disabled') || step == nextStep) return;

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
    var step = DataStorage.step;
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
    var chart = DataStorage.clone('chart');
    var isPublished = chart.stage == 'published';

    if(stage == 'published' && !validate()) return;
    if(stage == 'draft')
    {
        var chart       = DataStorage.clone('chart');
        var newSql      = $('#sql').val().replace(';', "").trim();
        var hasSettings = checkSettings();
        var sqlChange   = chart.sql.length > 0 && newSql !== chart.sql;

        if(sqlChange && hasSettings)
        {
            bootbox.confirm(clearSettingsLang, function(res)
            {
                if(res)
                {
                    /* Init chart design settings */
                    chart.settings = [{type: 'pie'}];
                    /* Init filter settings */
                    chart.filters = [];
                    loadConfigForm('pie', true);
                    buildSettingForm(true);

                    if(chart.used)
                    {
                        bootbox.confirm({message:confirmDraft,callback:save.bind(null, stage)});
                    }
                    else
                    {
                        if(isPublished) bootbox.confirm({message:chartLang.draftSave,callback:save.bind(null, stage)});
                        if(!isPublished) save(stage);
                    }
                    DataStorage.chart = chart;
                }
            });
        }
        else
        {
            if(chart.used)
            {
                bootbox.confirm({message:confirmDraft,callback:save.bind(null, stage)});
            }
            else
            {
                if(isPublished) bootbox.confirm({message:chartLang.draftSave,callback:save.bind(null, stage)});
                if(!isPublished) save(stage);
            }
        }

        return;
    }

    if(chart.used)  bootbox.confirm({message:confirmPublish,callback:save.bind(null, stage)});
    if(!chart.used) save(stage)
}

function save(stage, status = true)
{
    if(!status || DataStorage.saving) return;

    DataStorage.saving  = true;
    var chart         = DataStorage.clone('chart');
    var fields        = DataStorage.fields;
    var fieldSettings = DataStorage.fieldSettings;
    var langs         = DataStorage.langs;
    var sqlbuilder    = DataStorage.sqlbuilder;
    if(sqlbuilder && sqlbuilder.sql !== undefined) sqlbuilder.sql = window.latin1ToBase64(sqlbuilder.sql);

    chart.sql           = window.latin1ToBase64($('#sql').val().replace(';', "").trim());
    chart.step          = DataStorage.step;
    chart.mode          = DataStorage.mode;
    chart.fields        = Object.keys(fields);
    chart.fieldSettings = fieldSettings;
    chart.langs         = langs;
    chart.stage         = stage;
    chart.type          = chart.settings[0].type;
    chart.acl           = $('input[name="acl"]:checked').val();
    chart.whitelist     = $('select[name="whitelist[]"]').val();
    chart.sqlbuilder    = JSON.stringify(sqlbuilder);
    DataStorage.chart = chart;

    $.post(createLink('chart', 'design', 'chartID=' + chart.id), chart, function(response)
    {
        response = JSON.parse(response);

        if(response.result == 'success')
        {
            success(response.message);
            setTimeout(function()
            {
                window.location.href = createLink('chart', 'browse')
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

        /* Update sql query values */
        getFieldSettings();
        var newSql      = $('#sql').val().replace(';', "").trim();
        var hasSettings = checkSettings();

        var chart = DataStorage.clone('chart');
        /* Fix bug #32348. */
        chart.sqlChange = chart.sql.length > 0 && newSql !== chart.sql;

        if(chart.sqlChange && hasSettings)
        {
            bootbox.confirm(resetSettingsLang, function(res)
            {
                if(res)
                {
                    /* Remove step active. */
                    $('#step1').nextAll('.step-tab').removeClass('active');
                    /* Set chart refreshTab for function getFinish. */
                    chart.refreshTab = true;
                    chart.stage      = 'draft';
                    /* Init chart design settings */
                    chart.settings = [{type: 'pie'}];
                    /* Init filter settings */
                    chart.filters = [];
                    loadConfigForm('pie', true);
                    buildSettingForm(true);

                    chart.sql           = newSql;
                    chart.fields        = Object.keys(fields);
                    chart.fieldSettings = fieldSettings;
                    DataStorage.chart = chart;

                    if(callback instanceof Function) callback(2);
                }
            });
            return false;
        }

        chart.sql           = newSql;
        chart.fields        = Object.keys(fields);
        chart.fieldSettings = fieldSettings;
        DataStorage.chart = chart;
    }

    if(step == 2)
    {
        var check = checkStep2();
        if(!check) return false;
    }

    if(callback instanceof Function) callback(nextStep);
}

/**
 * Load chart config form.
 *
 * @param string type
 * @access public
 * @return void
 */
function loadConfigForm(type, update = false)
{
    if(echart) echart.clear();
    var chart = DataStorage.clone('chart');
    chart.type = type;
    chart.settings = [{type: type}];
    DataStorage.chart = chart;

    if(update) $('select#type').data('zui.picker').setValue(type);

    /* Show current html form. */
    buildSettingForm(true, function()
    {
        if(validate()) ajaxGetChart();
    });
}

/**
 * Listen pie config form change.
 *
 * @param string value
 * @param string field
 * @param bool required
 * @access public
 * @return void
 */
function pieChange(value, field, required = false, multi = 0)
{
    if(multi)
    {
        refreshMulti(this);
    }
    else
    {
        var chart = DataStorage.clone('chart');
        /* Pie have group and metirc, others have xaxis. */
        var isEmpty = value == '';
        if(field == 'group' || field == 'xaxis')
        {
            var fieldName = isEmpty ? '' :  DataStorage.fieldSettings[value].name;
            var fieldType = isEmpty ? '' :  DataStorage.fieldSettings[value].type;
            var dateClass = fieldType == 'date' ? '' : 'hidden';

            $('#dateGroup').removeClass();
            $('#dateGroup').addClass(dateClass);
            clearRadioChecked();
            chart.settings[0][field] = [{field: value, name: fieldName, group: ''}];
        }
        else if(field == 'metric')
        {
            var agg       = $('#valOrAgg').val();
            var fieldName = isEmpty ? '' : DataStorage.fieldSettings[value].name;
            chart.settings[0][field] = [{field: value, name: fieldName, valOrAgg: agg}];
        }
        else if(field == 'dateGroup')
        {
            var type  = chart.settings[0].type;
            var field = type == 'pie' ? 'group' : 'xaxis';
            chart.settings[0][field][0].group = value;
        }
        else if(field == 'valOrAgg')
        {
            var field     = $('#metric').val();
            var fieldName = DataStorage.fieldSettings[field].name;
            chart.settings[0]['metric'] = [{field: field, name: fieldName, valOrAgg: value}];
        }
        else if(field == 'rotateX' || field == 'rotateY')
        {
            var isChecked = $('input#' + field + '0')[0].checked;
            chart.settings[0][field] = isChecked ? 'use' : 'notuse';
        }
        DataStorage.chart = chart;
    }

    refreshChart();
}

/**
 * Build setting form.
 *
 * @access public
 * @return void
 */
function buildSettingForm(dropOld = false, callback = '')
{
    var chart         = DataStorage.chart;
    var fieldSettings = DataStorage.fieldSettings;
    var langs         = DataStorage.langs;

    var type = chart.settings[0].type;
    var $form = $('#chartForm');

    if(dropOld)
    {
        $form.empty();
        var fieldNames = {};
        Object.keys(fieldSettings).forEach(function(key){fieldNames[key] = fieldSettings[key].name});
        $.post(createLink('chart', 'ajaxGetTypeForm', 'id=' + chart.id + '&type=' + type),
        {
            fields:        fieldNames,
            values:        chart.settings[0],
            fieldSettings: fieldSettings,
            langs:         langs,
            sql:           window.latin1ToBase64(chart.sql)
        }, function(data)
        {
            $form.html($(data));
            initPicker($form);

            var type = chart.settings[0].type;
            if(type == 'waterpolo') $('.denominator-tip').popover();
            if(['line', 'cluBarX', 'cluBarY', 'stackedBar', 'stackedBarY'].includes(type)) $('.rotate-tip').popover();

            if(callback instanceof Function) callback();
        });
    }
}

/**
 * Add row.
 *
 * @param  obj $obj
 * @param  field $field
 * @access public
 * @return void
 */
function addRow(obj, field)
{
    var chart = DataStorage.chart;
    var type = chart.settings[0].type;
    var tplName = '#' + type + 'Column';
    var row = $(tplName).html();
    $('<tr class="addedRow">' + row  + '</tr>').insertAfter($(obj).closest('tr'));

    var butClass = '.del-' + field;
    var delCount =  $(obj).parents('tbody').find('.del-' + field).length;
    if(delCount == 2) $(obj).parents('tbody').find('.del-' + field).eq(0).removeClass('hidden');

    var $row = $(obj).closest('tr').next();
    initPicker($row, 'hidden-picker-select');
}

/**
 * Delete row.
 *
 * @param  obj $obj
 * @param  field $field
 * @access public
 * @return void
 */
function deleteRow(obj, field)
{
    var chart = DataStorage.chart;
    var type = chart.settings[0].type;
    var buttons  = $('#chartForm .table-form .del-' + field);
    var delCount = buttons.length;
    var th       = buttons.eq(0).parents('tr').find('th').text();

    $(obj).closest('tr').remove();

    if(delCount === 2) $('#chartForm .del-' + field).eq(0).addClass('hidden');
    $('#chartForm .del-' + field).eq(0).parents('tr').find('th').text(th);

    var isReady = validate();
    refreshMulti();
    refreshChart();
}

/**
 * Refresh multi form values.
 *
 * @access public
 * @return void
 */
function refreshMulti()
{
    var chart         = DataStorage.clone('chart');
    var fieldSettings = DataStorage.fieldSettings;
    var type          = chart.settings[0].type;
    var chartSetting  = chartSettings[type];
    var multiColumn   = multiColumns[type];
    var multiSettings = chartSetting[multiColumn];
    var fields        = multiSettings.map(function(item){return item.field});

    var settingInfo = {};
    fields.forEach(function(field)
    {
        settingInfo[field] = [];
        $('#chartForm .table-form').find('.multi-' + field).each(function()
        {
            settingInfo[field].push($(this).val());
        });
    });

    /* Set chart.settings[0]['yaxis']. */
    var yaxisSetting = [];
    for(var index = 0; index < settingInfo.yaxis.length; index ++)
    {
        var yaxis     = settingInfo.yaxis[index];
        if(yaxis.length == 0) continue;

        var fieldName = fieldSettings[yaxis].name;
        var valOrAgg  = settingInfo.valOrAgg[index];
        yaxisSetting.push({field: yaxis, name: fieldName, valOrAgg: valOrAgg});
    }
    chart.settings[0]['yaxis'] = yaxisSetting;
    DataStorage.chart = chart;
}

/**
 * Refresh chart draw.
 *
 * @access public
 * @return void
 */
function refreshChart()
{
    var isReady = validate();
    if(isReady)
    {
        ajaxGetChart();
    }
    else
    {
        echart.clear();
    }
}

/**
 * Get current finish step stage.
 *
 * @access public
 * @return int
 */
function getFinish()
{
    var chart = DataStorage.clone('chart');
    if('refreshTab' in chart)
    {
        delete chart.refreshTab;
        DataStorage.chart = chart;
        return 1;
    }
    if(chart.stage == 'published' || chart.stage == 4) return 4;

    if(checkStep2(false)) return 2;

    if(checkStep1(false)) return 1;

    return 0;
}

function checkSettings()
{
    var chart = DataStorage.chart;
    var type = chart.settings[0].type;
    var chartSetting = chartSettings[type];

    for(var key in chartSetting)
    {
        var exist = chartSetting[key].find(function(field)
        {
            return field.required && chart.settings[0][field.field];
        });

        if(exist) return true;
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
    var chart         = DataStorage.chart;

    if(!page)
    {
        var sql           = chart.sql;
        var fields        = DataStorage.fields;
        var fieldSettings = DataStorage.fieldSettings;

        return sql && fieldSettings && fields && sql.length > 0 && Object.keys(fieldSettings).length > 0 && Object.keys(fields). length > 0;
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
    if(!page)
    {
        var settings = DataStorage.chart.settings;
        return settings instanceof Array && settings.length > 0 && validate();
    }
    return validate(true);
}

function switchStep(step)
{
    setStepActive(step);
    setStepEnable(step);
    buildSettingForm(true, initStep(step));
}

function initStep(step)
{
    if(step == 1) return;
    if(echart) echart.dispose();
    var echartDom = $('#step' + step + 'Content').find('#draw').get(0);
    echart = echarts.init(echartDom);
    noDataDom = $('#step' + step + 'Content').find('#draw-no-data');

    if(validate()) ajaxGetChart();

    if(step == 2 || step == 3 || step == 4) renderFilters(step);
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

function clearRadioChecked()
{
    $("#dateGroupday").prop('checked', false);
    $("#dateGroupdweek").prop('checked', false);
    $("#dateGroupmonth").prop('checked', false);
    $("#dateGroupyear").prop('checked', false);
}

function toggleRadioSelection(obj, value)
{
    var radio1 = $("#dateGroupday");
    var radio2 = $("#dateGroupweek");
    var radio3 = $("#dateGroupmonth");
    var radio4 = $("#dateGroupyear");
    var clickedRadio = $('#' + $(obj).attr('id'));

    if(clickedRadio.hasClass('checked'))
    {
        clickedRadio.prop('checked', false);
        clickedRadio.removeClass('checked');
    }
    else
    {
        clearRadioChecked();
        radio1.removeClass('checked');
        radio2.removeClass('checked');
        radio3.removeClass('checked');
        radio4.removeClass('checked');

        clickedRadio.prop('checked', true);
        clickedRadio.addClass('checked');
    }

    var sendValue = clickedRadio.hasClass('checked') ? value : '';
    pieChange(sendValue, 'dateGroup');
}
