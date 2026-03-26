function addWaterpoloRow(obj)
{
    var tpl        = $('#conditionTpl').html();
    var $parentDiv = $(obj).parents('.condition-div');
    $('<div class="molecule-div condition-div">' + tpl  + '</tr>').insertAfter($parentDiv);

    var $addDiv = $parentDiv.next();
    $addDiv.find('#field').picker();
    $addDiv.find('#value').picker();
    $addDiv.find('#field').next().addClass('required');
    $addDiv.find('#value').next().addClass('required');

    var delCount = $('.molecule-div').find('.del-condition').length;
    if(delCount >= 2) $('.molecule-div').find('.del-condition').eq(0).removeClass('hidden');

    refreshConditionText();
}

function deleteWaterpoloRow(obj)
{
    $(obj).parents('.condition-div').remove();
    var delCount = $('.molecule-div').find('.del-condition').length;
    if(delCount == 1) $('.molecule-div').find('.del-condition').eq(0).addClass('hidden');

    refreshConditionText();
    refreshConditions();
    refreshChart();
}

function refreshConditionText()
{
    $('#chartForm .table-form .molecule-div').find('.text-condition').each(function(index)
    {
        var text = index == 0 ? chartLang.condition : chartLang.and;
        $(this).text(text);
    });
}

function validateWaterpolo(showError)
{
    var chart        = DataStorage.chart;
    var formSettings = chart.settings[0];
    var type         = formSettings.type;
    var chartSetting = chartSettings[type];
    var multiColumn  = multiColumns[type];

    var isReady = true;

    ['goal', 'calc'].forEach(function(field)
    {
        var error = '<div id="' + field + 'Label"' + ' class="text-danger help-text">' + chartLang.fieldEmpty + '</div>';

        $('#' + field + 'Label').remove();
        $('#' + field).removeClass('has-error');
        if(!formSettings[field] || formSettings[field] == '')
        {
            isReady = false;
            if(showError)
            {
                $('#' + field).addClass('has-error');
                $('#' + field).next().after(error);
            }
        }
    });

    ['field', 'value'].forEach(function(field)
    {
        var error = '<div id="' + field + 'Label"' + ' class="text-danger help-text">' + chartLang.fieldEmpty + '</div>';
        $('#chartForm .table-form .molecule-div').find('.multi-' + field).each(function()
        {
            $(this).parent('div').find('#' + field + 'Label').remove();
            $(this).parent('div').find('#' + field).removeClass('has-error');

            if($(this).val().length == 0)
            {
                isReady = false;
                if(showError)
                {
                    $(this).parent('div').find('#' + field).addClass('has-error');
                    $(this).parent('div').find('#' + field).next().after(error);
                }
            }
        })
    });

    return isReady;
}

async function waterpoloChange($obj)
{
    $obj = $($obj);
    var chart = DataStorage.clone('chart');

    var nowField = $obj.attr('id')
    var nowValue = $obj.val();

    if(nowField == 'goal')
    {
        chart.settings[0]['calc'] = '';
        await updateCalcList(nowValue);
    }
    if(nowField == 'field') await updatefieldValue($obj, nowValue);

    if(['goal', 'calc'].includes(nowField))
    {
        chart.settings[0][nowField] = $('#chartForm .table-form').find('#' + nowField).val();
        DataStorage.chart = chart;
        updateDenominatorTip(chart.settings[0]);
    }
    else
    {
        refreshConditions();
    }

    refreshChart();
}

function refreshConditions()
{
    var chart = DataStorage.clone('chart');

    var settingInfo = {};
    ['field', 'value'].forEach(function(field)
    {
        settingInfo[field] = [];
        $('#chartForm .table-form .molecule-div').find('.multi-' + field).each(function()
        {
            settingInfo[field].push($(this).val());
        });
    });

    var conditions = [];
    for(var index = 0; index < settingInfo.field.length; index ++)
    {
        var field = settingInfo.field[index];
        if(field.length == 0) continue;

        var value  = settingInfo.value[index];
        conditions.push({field: field, condition: 'eq', value: value});
    }

    chart.settings[0]['conditions'] = conditions;
    DataStorage.chart = chart;
}

async function updateCalcList(fieldValue)
{
    var type = fieldValue == '' ? '' : DataStorage.fieldSettings[fieldValue].type;
    return new Promise(function(resolve, reject)
    {
        $.post(createLink('chart', 'ajaxGetWaterpoloCalcOption'),
        {
            type: type
        }, function(data)
        {
            var $calcParent = $('#chartForm .table-form').find('#calc').parent();
            $calcParent.empty();
            $calcParent.append(data);
            $calcParent.find('#calc').picker();
            $calcParent.find('#calc').next().addClass('required');
            resolve();
        });
    });
}

async function updatefieldValue($obj, fieldValue)
{
    var chart = DataStorage.clone('chart');

    return new Promise(function(resolve, reject)
    {
        $.post(createLink('chart', 'ajaxGetWaterpoloFieldOption'),
        {
            sql:          chart.sql,
            fieldSetting: fieldValue ? DataStorage.fieldSettings[fieldValue] : []
        }, function(data)
        {
            var $valueParent = $obj.parents('.molecule-div').find('#value').parent();
            $valueParent.empty();
            $valueParent.append(data);
            $valueParent.find('#value').picker();
            $valueParent.find('#value').next().addClass('required');
            resolve();
        });
    });
}

function updateDenominatorTip(setting)
{
    if(setting['goal'] !== undefined && setting['goal'] != '' && setting['calc'] !== undefined && setting['calc'] != '')
    {
        $.post(createLink('chart', 'ajaxGetDenominatorTip'),
        {
            setting: setting,
            fieldSettings: DataStorage.fieldSettings,
            langs: DataStorage.langs
        }, function(data)
        {
            $('.text-denominatorTip').text(data);
        });
    }
    else
    {
        $('.text-denominatorTip').text(chartLang.denominatorTip.empty);
    }
}
