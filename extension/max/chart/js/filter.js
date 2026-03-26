$(function()
{
    $(document).on('click', '.add-filter', addFilter);
});

function calcDesignGrowFilter(resize = false)
{
    var step = DataStorage.step;
    var chart = DataStorage.chart;
    /* When body resize, resize echarts. */
    var stepContent = $('#step' + step + 'Content');
    if(resize)
    {
        if(step == 1) return;
        var echartDom   = stepContent.find('#draw').get(0);
        var echart = echarts.init(echartDom);
        echart.resize();
    }

    /* Check whether the filter should be calc. */
    if(step == 1) return;
    if(chart.filters.length <= 0) return;

    var $filterItems = stepContent.find('#filterItems');
    /* When refreshing this page, ZenTao load this page twice, when the first load isn't complete, jump back to index and load the second time,
       the first load can't calc filter width, can't get the element using jQuery at first load. */
    var hasInit = true;
    chart.filters.forEach(function(filter, index)
    {
        var nowItem    = '.filter-item-' + index;
        var $titleSpan = $filterItems.find(nowItem).find('.input-group-addon').first();
        if(!$titleSpan.length) hasInit = false;
    });
    if(!hasInit) return;

    var domWidth     = $filterItems[0].getBoundingClientRect().width;
    var nowWidth     = domWidth;
    var lineWrap     = false;
    var nowCount     = 0;
    var canGrowTotal = 0;

    $('.query-inside').addClass('hidden');
    $('#queryButton' + step).addClass('visibility-hidden');

    chart.filters.forEach(function(filter, index)
    {
        var nowItem      = '.filter-item-' + index;
        var $nowDom      = $filterItems.find(nowItem);
        var leftPadding  = parseInt($nowDom.css('padding-left'));
        var rightPadding = parseInt($nowDom.css('padding-right'));
        var spanWidth    = $nowDom.find('.input-group-addon').first()[0].getBoundingClientRect().width;
        var filterWidth  = ((filter.type == 'input' || filter.type == 'select') ? WIDTH_INPUT : WIDTH_DATE) + (spanWidth + leftPadding + rightPadding);
        if(filter.type == 'select' && $nowDom.find('.picker').length) $nowDom.find('.picker').find('.picker-selections').css('width', WIDTH_INPUT);

        /* Clear the flex-basis and set flex-basic again. */
        $nowDom.css('flex-basis', '');
        $nowDom.css('flex-basis', filterWidth);
        if(nowWidth - filterWidth >= 0)
        {
            nowWidth -= filterWidth;
            nowCount ++;
        }
        else
        {
            canGrowTotal += nowCount;
            nowWidth = domWidth - filterWidth;
            nowCount = 1;
            lineWrap = true;
        }
    });

    /* Clear all filter filter-item-grow and add class to support grow element. */
    $filterItems.children().removeClass('filter-item-grow');
    chart.filters.forEach(function(filter, index)
    {
        var $nowDom = $filterItems.find('.filter-item-' + index);
        if(canGrowTotal >= index + 1) $nowDom.addClass('filter-item-grow');
    });

    if(!lineWrap && nowWidth >= 60) $('.query-inside').removeClass('hidden');
    else $('#queryButton' + step).removeClass('visibility-hidden');

    /* Set picker-selection width, default 128px. */
    waitForRepaint(function()
    {
        chart.filters.forEach(function(filter, index)
        {
            var $nowDom = $filterItems.find('.filter-item-' + index);
            if(filter.type == 'select' && $nowDom.find('.picker').length)
            {
                var pickerWidth = $nowDom.hasClass('filter-item-grow') ? $nowDom.find('.picker')[0].getBoundingClientRect().width : WIDTH_INPUT;
                $nowDom.find('.picker').find('.picker-selections').css('width', pickerWidth);
            }
        });
    });

}

function waitForRepaint(callback)
{
    window.requestAnimationFrame(function()
    {
        window.requestAnimationFrame(callback);
    });
}
/**
 * Add a filter.
 *
 * @access public
 * @return void
 */
function addFilter()
{
    var fields        = DataStorage.fields;
    var langs         = DataStorage.langs;
    var fieldSettings = DataStorage.fieldSettings;

    var field     = Object.keys(fields)[0];
    var fieldName = typeof(langs[field]) != 'undefined' ? (langs[field][clientLang] ? langs[field][clientLang] : fields[field]) : fieldSettings[field].name;
    var filter    = {field: field, saveAs: '', type: 'input', name: fieldName, default: ''};

    var chart = DataStorage.clone('chart');
    chart.filters.push(filter);
    DataStorage.chart = chart;
    renderFilters();
}

/**
 * Remove a filter.
 *
 * @access public
 * @return void
 */
function removeFilter(index)
{
    var chart = DataStorage.clone('chart');
    chart.filters.splice(index, 1);
    DataStorage.chart = chart;
    renderFilters();
}

function renderFilters(step = 3)
{
    var chart         = DataStorage.chart;
    var fieldSettings = DataStorage.fieldSettings;
    var langs         = DataStorage.langs;

    if(step == 2)
    {
        $('#step2Content #filterItems').empty();
        if(chart.filters.length > 0) $('.queryButton').removeClass('hidden');
    }
    if(step == 3)
    {
        $('#step3Content #filterForm').empty();
        $('#step3Content #filterItems').empty();
    }
    if(step == 4)
    {
        $('#step4Content #filterItems').empty();
        if(chart.filters.length > 0) $('.queryButton').removeClass('hidden');
    }

    if(chart.filters.length == 0)
    {
        $('.queryButton').addClass('hidden');
        $('#filter-tip').removeClass('hidden');
        $('#filterContent').addClass('hidden');
        resizeChart(step);
        return;
    }
    else
    {
        $('#filter-tip').addClass('hidden');
        $('#filterContent').removeClass('hidden');
    }

    var fieldNames = {};
    Object.keys(fieldSettings).forEach(function(key){fieldNames[key] = fieldSettings[key].name;});
    $.post(createLink('chart', 'ajaxGetFilterForm', 'chartID=' + chart.id), {fieldList: fieldNames, fieldSettings: fieldSettings, filters: chart.filters, langs: DataStorage.langs, sql: $('#sql').val()}, function(resp)
    {
        resp = JSON.parse(resp);
        chart.filters.forEach(function(filter, index)
        {
            if(step == 3) renderFilterForm(filter, resp, index);

            renderFilterItem(filter, resp, index, DataStorage.step);
        });
        $('#step' + step + 'Content #filterItems').append(queryDom);

        calcDesignGrowFilter();
        resizeChart(step);
    });
    if(chart.filters.length > 0) $('.queryButton').removeClass('hidden');
}

function renderFilterForm(filter, resp, index)
{
    var tpl = $('#step3Content #filterTpl').html();
    var data =
    {
        index: index,
        fieldName: filter.name,
        fieldSelect: resp[index].field,
        saveAs: resp[index].saveAs,
        saveAsClass: resp[index].saveAsClass,
        type: resp[index].type,
        filterName: filter.name,
        defaultControl: resp[index].default
    };
    var html = $($.zui.formatString(tpl, data))
    initPicker(html);
    initDatepicker(html);
    if(filter.type == 'condition') html.find('.picker').css('width', '100%');
    $('#step3Content #filterForm').append(html);
}

function renderFilterItem(filter, resp, index, step)
{
    var tpl = $('#step' + step + 'Content #filterItemTpl').html();
    var data =
    {
        index: index,
        name: filter.name,
        search: resp[index].item
    };
    var html = $($.zui.formatString(tpl, data))
    initPicker(html);
    initDatepicker(html, attrDateCheck);
    if(filter.type == 'condition') html.find('.picker').css('width', '100%');
    $('#step' + step + 'Content #filterItems').append(html);
}

function getFilterID(obj)
{
    return $(obj).closest('.filter').attr('class').replace('filter filter-', '');
}

function getFilterField(obj)
{
    return $(obj).closest('.filter').find('#field').val();
}

/**
 * Init a filter form.
 *
 * @param  sting fieldSelect
 * @param  int   fieldIndex
 * @access public
 * @return void
 */
function initFilterForm(fieldSelect, fieldIndex)
{
    var fields = DataStorage.fields;
    var fieldSettings = DataStorage.fieldSettings;
    var chart = DataStorage.clone('chart');

    var fieldName = $(fieldSelect).closest('td').find('.picker-selection-text').text();
    var filterID  = getFilterID(fieldSelect);
    $(fieldSelect).closest('.filter').find('#name').val(fieldName);

    $('#step3Content #filterItems .filter-item-' + filterID + ' .field-name').text(fieldName);
    $('#step3Content #sidebar .filter-' + filterID + ' .filter-title').find('span').text(chartLang.resultFilter + '-' + fieldName);

    chart.filters[filterID].field = fieldIndex;
    chart.filters[filterID].name  = fieldName;
    DataStorage.chart = chart;

    $type = $(fieldSelect).closest('.filter').find('#type');
    changeType($type, $type.val(), false);
    changeName($(fieldSelect).closest('.filter').find('#name'), fieldName);
}

/**
 * Set default value.
 *
 * @param  string typeSelect
 * @param  string type
 * @access public
 * @return void
 */
function changeType(typeSelect, type, refreshSaveAs = true)
{
    var fieldSettings = DataStorage.fieldSettings;
    var chart = DataStorage.clone('chart');

    var filterID = getFilterID(typeSelect);

    var spanName = $('#step3Content #filterItems .filter-item-'  + filterID).find('span').html();
    var spanHtml = "<span class='field-name input-group-addon'>" + spanName + "</span>";

    var $defaultTd  = $(typeSelect).closest('.filter-body').find('.default-line td');
    var defaultHTML = '<input type="text" name="default" id="default" value="" class="form-control" autocomplete="off" onchange="changeDefault(this,this.value)">';
    if(type == 'select')
    {
        var field        = getFilterField(typeSelect);
        var fieldSetting = fieldSettings[field];
        var $default     = $(typeSelect).closest('.filter').find('.default-line > td');
        var $fieldSearch = $('#step3Content #filterItems .filter-item-'  + filterID);

        updateSelectOption(filterID, fieldSetting, $default, $fieldSearch)
    }
    else if(type == 'condition')
    {
        setConditionOptions(typeSelect);
    }
    else if(type == 'date')
    {
        defaultHTML = '<div class="input-group"><input type="text" name="default[begin]" id="default[begin]" class="form-control form-date default-begin" autocomplete="off" placeholder="' + chartLang.unlimited + '" onchange="changeDefault(this,this.value)"><span class="input-group-addon fix-border  borderBox" style="border-radius: 0px;">' + chartLang.colon + '</span><input type="text" name="default[end]" id="default[end]" class="form-control form-date default-end" autocomplete="off" placeholder="' + chartLang.unlimited + '" onchange="changeDefault(this,this.value)"></div>';
    }
    else if(type == 'datetime')
    {
        defaultHTML = '<div class="input-group"><input type="text" name="default[begin]" id="default[begin]" class="form-control form-datetime default-begin" autocomplete="off" placeholder="' + chartLang.unlimited + '" onchange="changeDefault(this,this.value)"><span class="input-group-addon fix-border  borderBox" style="border-radius: 0px;">'  + chartLang.colon + '</span><input type="text" name="default[end]" id="default[end]" class="form-control form-datetime default-end" autocomplete="off" placeholder="' + chartLang.unlimited + '" onchange="changeDefault(this,this.value)"></div>';
    }
    $defaultTd.empty();
    $defaultTd.html(defaultHTML);
    $defaultTd.find('.chosen').chosen();
    initDatepicker($defaultTd);

    var $fieldSearch = $('#step3Content #filterItems .filter-item-'  + filterID);
    $fieldSearch.html(spanHtml + defaultHTML);
    $fieldSearch.find('input').removeAttr('onchange');
    $fieldSearch.find('.chosen').removeAttr('onchange');
    $fieldSearch.find('.chosen').chosen();
    initDatepicker($fieldSearch, attrDateCheck);

    chart.filters[filterID].type = type;
    if(type == 'input') chart.filters[filterID].default = '';
    if(type == 'date' || type == 'datetime') chart.filters[filterID].default = {begin: '', end: ''};
    if(type == 'select') chart.filters[filterID].default = [];
    if(type == 'condition')
    {
        chart.filters[filterID].operator = '=';
        chart.filters[filterID].value = '';
    }
    if(refreshSaveAs)
    {
        chart.filters[filterID].saveAs = '' // 切换类型的时候，重置saveAs字段
        $(typeSelect).closest('.filter').find('#saveAs').data('zui.picker').setValue('');
    }

    DataStorage.chart = chart;
    toggleSaveAsShow(typeSelect, type == 'select');
    calcDesignGrowFilter();
}

function toggleSaveAsShow(obj, show = true)
{
    var $saveAs = $(obj).closest('.filter').find('.saveAs');
    if(show)
    {
        $saveAs.removeClass('hidden');
    }
    else
    {
        $saveAs.addClass('hidden');
    }
}

function changeSaveAs(saveAsSelect, fieldSaveAs)
{
    var filterID = getFilterID(saveAsSelect);

    var chart = DataStorage.clone('chart');
    chart.filters[filterID].saveAs = fieldSaveAs;
    DataStorage.chart = chart;

    var field         = getFilterField(saveAsSelect);
    var fieldSettings = DataStorage.fieldSettings;
    var fieldSetting  = fieldSettings[field];
    var $default      = $(saveAsSelect).closest('.filter').find('.default-line > td');
    var $fieldSearch  = $('#step3Content #filterItems .filter-item-'  + filterID);

    updateSelectOption(filterID, fieldSetting, $default, $fieldSearch);
}

function updateSelectOption(filterID, fieldSetting, $default, $fieldSearch)
{
    var chart  = DataStorage.clone('chart');
    var filter = chart.filters[filterID];
    var saveAs = 'saveAs' in filter ? filter.saveAs : '';

    var chartParams = {sql: $('#sql').val()};
    $.post(createLink('chart', 'ajaxGetSysOptions', "type=" + fieldSetting.type + "&object=" + fieldSetting.object + "&field=" + fieldSetting.field + "&saveAs=" + saveAs), chartParams, function(options)
    {
        if(!options) options = '<select id="default" name="default" class="form-control" onchange="changeDefault(this,this.value)"></select>';
        $default.empty();
        $default.html(options);
        initPicker($default);

        var spanName = $('#step3Content #filterItems .filter-item-'  + filterID).find('span').html();
        var spanHtml = "<span class='field-name input-group-addon'>" + spanName + "</span>";
        $fieldSearch.empty();
        $fieldSearch.html(spanHtml + options);
        $fieldSearch.find('#default').removeAttr('onchange');
        initPicker($fieldSearch, 'picker-select', true);
    });
}

/**
 * Set condition options.
 *
 * @param  string $typeSelect
 * @param  string $fieldSetting
 * @access public
 * @return void
 */
function setConditionOptions(typeSelect, fieldSetting)
{
    var filterID     = $(typeSelect).closest('.filter').attr('class').replace('filter filter-', '');
    var $default     = $(typeSelect).closest('.filter').find('.default-line > td');
    var $fieldSearch = $('#step3Content #filterItems .filter-item-'  + filterID);

    $.get(createLink('chart', 'ajaxGetConditionOptions'), function(options)
    {
        $default.empty();
        $default.html(options);
        initPicker($default);
        $default.find('.picker').css('width', '100%');

        var spanName = $('#step3Content #filterItems .filter-item-'  + filterID).find('span').html();
        var spanHtml = "<span class='field-name input-group-addon'>" + spanName + "</span>";
        $fieldSearch.empty();
        $fieldSearch.html(spanHtml + options);
        $fieldSearch.find('#operator').removeAttr('onchange');
        $fieldSearch.find('#operator').attr('onchange', 'changeCondition(this,this.value,true)');
        $fieldSearch.find('#value').removeAttr('onchange');
        initPicker($fieldSearch);
        $fieldSearch.find('.picker').css('width', '100%');
    });
}

/**
 * Change filter name.
 *
 * @param  string $nameSelect
 * @param  string $name
 * @access public
 * @return void
 */
function changeName(nameSelect, name)
{
    if(name == '')
    {
        var error = '<div id="groupLabel" class="text-danger help-text">' + nameEmpty + '</div>';
        $(nameSelect).addClass('has-error');
        $(nameSelect).after(error);
        disableButton(true);
        return;
    }
    else
    {
        disableButton(false)
    }

    var filterID = $(nameSelect).closest('.filter').attr('class').replace('filter filter-', '');
    $('#step3Content #filterItems .filter-item-' + filterID + ' .field-name').text(name);
    $('#step3Content #sidebar .filter-' + filterID + ' .filter-title').find('span').text(chartLang.resultFilter + '-' + name);
    $('#step3Content #sidebar .filter-' + filterID + ' #name').removeClass('has-error');
    $('#step3Content #sidebar .filter-' + filterID + ' #name').siblings().remove();

    var chart = DataStorage.clone('chart');
    chart.filters[filterID].name = name;
    DataStorage.chart = chart;
    calcDesignGrowFilter();
}

/**
 * Disabled button if filter name is empty.
 *
 * @param  disable = true $disable = true
 * @access public
 * @return void
 */
function disableButton(disable = true)
{
    if(disable)
    {
        $('#step3Content .btn-save-setting').attr('disabled', true);
        $('#step3Content .btn-next-step').attr('disabled', true);
        $('#step3Content .add-filter').attr('disabled', true);
        $('#step3Content .queryButton .save-step').attr('disabled', true);
        $('.draft .btn-draft').attr('disabled', true);
        $('#step4').addClass('disabled');
    }
    else
    {
        $('#step3Content .btn-save-setting').attr('disabled', false);
        $('#step3Content .btn-next-step').attr('disabled', false);
        $('#step3Content .add-filter').attr('disabled', false);
        $('#step3Content .queryButton .save-step').attr('disabled', false);
        $('.draft .btn-draft').attr('disabled', false);
        $('#step4').removeClass('disabled');
    }
}

/**
 * Change default.
 *
 * @param  string defaultSelect
 * @param  string defaultValue
 * @access public
 * @return void
 */
function changeDefault(defaultSelect, defaultValue)
{
    var chart = DataStorage.clone('chart');
    var filterID     = $(defaultSelect).closest('.filter').attr('class').replace('filter filter-', '');
    var filterType   = chart.filters[filterID].type;
    var $filterValue = $('#step3Content #filterItems .filter-item-'  + filterID).find('#' + $(defaultSelect).attr('id'));
    if(filterType != 'select')
    {
        if($(defaultSelect).attr('id') == 'default[begin]' || $(defaultSelect).attr('id') == 'default[end]')
        {
            var dateClass = $(defaultSelect).hasClass('default-begin') ? '.default-begin' : '.default-end';
            $filterValue  = $('#step3Content #filterItems .filter-item-'  + filterID).find(dateClass);
            var canChange = checkDate(defaultSelect, $filterValue);
            if(!canChange) return;
        }
        $filterValue.val(defaultValue);
        var key = $(defaultSelect).hasClass('default-begin') ? 'begin' : 'end';

        if(filterType == 'input')
        {
            chart.filters[filterID].default = defaultValue;
        }
        else
        {
            chart.filters[filterID].default[key] = defaultValue;
        }

    }
    else
    {
        defaultValue = $(defaultSelect).val();
        /* Picker. */
        if($filterValue.hasClass('picker-select'))
        {
            $filterValue.data('zui.picker').setValue(defaultValue);
        }
        /* Chosen. */
        else
        {
            $filterValue.val(defaultValue);
            $filterValue.trigger('chosen:updated');
        }

        chart.filters[filterID].default = defaultValue;
    }
    DataStorage.chart = chart;
}

/**
 * Change default.
 *
 * @param  string defaultSelect
 * @param  string defaultValue
 * @param  bool   isSearch
 * @access public
 * @return void
 */
function changeCondition(defaultSelect, defaultValue, isSearch = false)
{
    var chart  = DataStorage.clone('chart');
    var hidden = defaultValue.indexOf('NULL') !== -1;
    $(defaultSelect).siblings('#value').removeClass('hidden');
    if(hidden)
    {
        $(defaultSelect).siblings('#value').addClass('hidden');
        $(defaultSelect).siblings('#value').val('');
    }

    if(!isSearch)
    {
        var filterID  = $(defaultSelect).closest('.filter').attr('class').replace('filter filter-', '');
        var $operator = $('#step3Content #filterItems .filter-item-'  + filterID).find('#operator');
        $operator.data('zui.picker').setValue(defaultValue);
        chart.filters[filterID].operator = defaultValue;

        if(hidden)
        {
            var $value = $('#step3Content #filterItems .filter-item-'  + filterID).find('#value');
            $value.val('');
            chart.filters[filterID].value = '';
        }
    }

    DataStorage.chart = chart;
}

/**
 * Change default.
 *
 * @param  string defaultSelect
 * @param  string defaultValue
 * @access public
 * @return void
 */
function changeConditionValue(defaultSelect, defaultValue)
{
    var filterID = $(defaultSelect).closest('.filter').attr('class').replace('filter filter-', '');
    var $value   = $('#step3Content #filterItems .filter-item-'  + filterID).find('#value');
    $value.val(defaultValue);

    var chart  = DataStorage.clone('chart');
    chart.filters[filterID].value = defaultValue;
    DataStorage.chart = chart;
}
