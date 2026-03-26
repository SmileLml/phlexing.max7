function calcDesignGrowFilter()
{
    var pivot = DataStorage.pivot;
    var step  = DataStorage.step;
    /* Check whether the filter should be calc. */
    if(step == 1) return;
    if(pivot.filters.length <= 0) return;

    var stepContent = $('#step' + step + 'Content');
    var $filterItems = stepContent.find('#filterItems');
    /* When refreshing this page, ZenTao load this page twice, when the first load isn't complete, jump back to index and load the second time,
       the first load can't calc filter width, can't get the element using jQuery at first load. */
    var hasInit = true;
    pivot.filters.forEach(function(filter, index)
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

    pivot.filters.forEach(function(filter, index)
    {
        var nowItem      = '.filter-item-' + index;
        var $nowDom      = $filterItems.find(nowItem);
        var leftPadding  = parseInt($nowDom.css('padding-left'));
        var rightPadding = parseInt($nowDom.css('padding-right'));
        var spanWidth    = $nowDom.find('.input-group-addon').first()[0].getBoundingClientRect().width;
        var filterWidth  = ((filter.type == 'input' || filter.type == 'select') ? WIDTH_INPUT : WIDTH_DATE) + (spanWidth + leftPadding + rightPadding);

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
    pivot.filters.forEach(function(filter, index)
    {
        var $nowDom = $filterItems.find('.filter-item-' + index);
        if(canGrowTotal >= index + 1) $nowDom.addClass('filter-item-grow');
        if(filter.type == 'select' && $nowDom.find('.picker').length) $nowDom.find('.picker').find('.picker-selections').css('width', WIDTH_INPUT);
    });

    if(!lineWrap && nowWidth >= 60) $('.query-inside').removeClass('hidden');
    else $('#queryButton' + step).removeClass('visibility-hidden');

    /* Set picker-selection width, default 128px. */
    waitForRepaint(function()
    {
        pivot.filters.forEach(function(filter, index)
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

/**
 * Add a filter.
 *
 * @access public
 * @return void
 */
function addFilter()
{
    var fields        = DataStorage.fields;
    var fieldSettings = DataStorage.fieldSettings;
    var langs         = DataStorage.langs;

    var field     = Object.keys(fields)[0];
    var fieldName = typeof(langs[field]) != 'undefined' ? (langs[field][clientLang] ? langs[field][clientLang] : fields[field]) : fieldSettings[field].name;
    var filter    = {field: field, saveAs: '', type: 'input', name: fieldName, default: ''};

    var pivot = DataStorage.clone('pivot');
    pivot.filters.push(filter);
    DataStorage.pivot = pivot;
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
    var pivot = DataStorage.clone('pivot');
    pivot.filters.splice(index, 1);
    DataStorage.pivot = pivot;
    renderFilters();

    var emptyName = pivot.filters.find(function(filter){return !filter.name || filter.name.length == 0});

    disableButton(!!emptyName);
}

methods.step3.query.changeVar = function(control, value)
{
    var filterBlock = $(control).closest('.filter');
    var filterIndex = filterBlock.attr('class').replace('filter filter-', '');

    var pivot = DataStorage.clone('pivot');
    var filter = pivot.filters[filterIndex];
    pivot.sql = pivot.sql.replaceAll('$' + filter.field, '$' + value);
    filter.field = value;
    pivot.filters[filterIndex] = filter;
    DataStorage.pivot = pivot;

    $('#sql').val(pivot.sql);
}
methods.step3.query.changeType = function(typeSelect, type)
{
    var filterBlock = $(typeSelect).closest('.filter');
    var filterIndex = filterBlock.attr('class').replace('filter filter-', '');

    var filter = DataStorage.updateFilter(filterIndex, {type: type, default: type == 'select' ? [] : ''});
    methods.step3.query.initControl(filterBlock.find('.default-line'), filter);
    methods.step3.query.renderFilterItem(DataStorage.pivot.filters);
}

methods.step3.query.changeDefault = function(control, value)
{
    var filterBlock = $(control).closest('.filter');
    var filterIndex = filterBlock.attr('class').replace('filter filter-', '');

    var pivot  = DataStorage.pivot;
    var filter = pivot.filters[filterIndex];
    if(filter.type == 'select')
    {
        value = $(control).data('zui.picker').getValue();
    }

    DataStorage.updateFilter(filterIndex, {default: value});
    methods.step3.query.renderFilterItem(DataStorage.pivot.filters);
}
methods.step3.query.renderFilterForm = function(filters)
{
    var tpl = $('#step3Content #queryFilterBlockTpl').html();
    var $filterForm = $('#step3Content #filterForm');
    $filterForm.empty();

    filters.forEach(function(filter, index)
    {
        var data =
        {
            index: index,
            field: filter.field,
            type: filter.type,
            filterName: filter.name,
        };
        var html = $($.zui.formatString(tpl, data))
        $('#step3Content #filterForm').append(html);
        $filterForm.append(html);
        if(index + 1 == filters.length) $filterForm.find('.filter-' + index).css('margin-bottom', '0');

        html.find('select[name="type"]').picker({defaultValue: filter.type});

        methods.step3.query.initControl(html.find('.default-line'), filter);
    });
}
methods.step3.query.renderFilterItem = function(filters, step)
{
    if(!step) step = DataStorage.step;

    $('#step' + step + 'Content #filterItems').empty();
    var tpl = $('#step' + step + 'Content #queryFilterItemTpl').html();

    filters.forEach(function(filter, index)
    {
        var data =
        {
            index: index,
            name: filter.name,
            defaultValue: filter.default
        };
        var html = $($.zui.formatString(tpl, data))
        $('#step' + step + 'Content #filterItems').append(html);

        methods.step3.query.initControl(html, filter);
    });
    $('#step' + step + 'Content #filterItems').append(queryDom);

    calcDesignGrowFilter();
}

methods.step3.query.initControl = function(container, filter, object, field)
{
    var type = filter.type;
    var options = filter.typeOption;
    var value = filter.default;

    container.find('.default-block').addClass('hidden');
    container.find('.default-block input[name="default"]').val('');

    var control = container.find('.form-' + type);
    control.val(filter.default);
    control.parent('.default-block').removeClass('hidden');

    if(type == 'date')     setDateField(control);
    if(type == 'datetime') setDateField(control);
    if(type == 'select')
    {
        var picker = control.data('zui.picker');
        if(picker) picker.destroy();

        $.post(createLink('pivot', 'ajaxGetSysOptions', 'type=' + options + '&object=' + object + '&field=' + field), function(resp)
        {
            control.html($(resp).html());
            control.picker({maxDropHeight: pickerHeight});
            control.data('zui.picker').setValue(value);
        });
    }
}

methods.step3.result.getFilterID = function(obj)
{
    return $(obj).closest('.filter').attr('class').replace('filter filter-', '');
}

methods.step3.result.getFilterField = function(obj)
{
    return $(obj).closest('.filter').find('#field').val();
}

methods.step3.result.getSpanHtml = function(filterID)
{
    var spanName = $('#step3Content #filterItems .filter-item-'  + filterID).find('span').html();
    var spanHtml = "<span class='field-name input-group-addon'>" + spanName + "</span>";

    return spanHtml;
}

methods.step3.result.changeField = function(control, value)
{
    var fieldName   = $(control).closest('td').find('.picker-selection-text').text();
    var filterBlock = $(control).closest('.filter');
    var filterID    = methods.step3.result.getFilterID(control);
    filterBlock.find('#name').val(fieldName);

    $('#step3Content #filterItems .filter-item-' + filterID + ' .field-name').text(fieldName);
    filterBlock.find('span.field-name-span').text(fieldName);

    var filter = DataStorage.updateFilter(filterID, {field: value, name: fieldName});
    changeName(filterBlock.find('#name'), fieldName);
    var $type = $(control).closest('.filter').find('#type');
    methods.step3.result.changeType($type, $type.val(), false);
}

methods.step3.result.changeType = function(typeSelect, type, refreshSaveAs = true)
{
    var fieldSettings = DataStorage.fieldSettings;
    var pivot = DataStorage.clone('pivot');

    var filterID = methods.step3.result.getFilterID(typeSelect);

    var $defaultTd  = $(typeSelect).closest('.filter-body').find('.default-line td');
    var defaultHTML = '<input type="text" name="default" id="default" value="" class="form-control form-input" autocomplete="off" onchange="methods.step3.result.changeDefault(this,this.value)">';
    if(type == 'date')
    {
        defaultHTML = '<div class="input-group"><input type="text" name="default[begin]" id="default[begin]" class="form-control form-date default-begin" autocomplete="off" placeholder="' + pivotLang.unlimited + '" onchange="methods.step3.result.changeDefault(this,this.value)"><span class="input-group-addon fix-border  borderBox" style="border-radius: 0px;">' + pivotLang.colon + '</span><input type="text" name="default[end]" id="default[end]" class="form-control form-date default-end" autocomplete="off" placeholder="' + pivotLang.unlimited + '" onchange="methods.step3.result.changeDefault(this,this.value)"></div>';
    }
    else if(type == 'datetime')
    {
        defaultHTML = '<div class="input-group"><input type="text" name="default[begin]" id="default[begin]" class="form-control form-datetime default-begin" autocomplete="off" placeholder="' + pivotLang.unlimited + '" onchange="methods.step3.result.changeDefault(this,this.value)"><span class="input-group-addon fix-border  borderBox" style="border-radius: 0px;">'  + pivotLang.colon + '</span><input type="text" name="default[end]" id="default[end]" class="form-control form-datetime default-end" autocomplete="off" placeholder="' + pivotLang.unlimited + '" onchange="methods.step3.result.changeDefault(this,this.value)"></div>';
    }

    /* Replace form and item. */
    if(type == 'select')
    {
        var field = methods.step3.result.getFilterField(typeSelect);
        var fieldSetting = fieldSettings[field];

        var $default     = $(typeSelect).closest('.filter').find('.default-line > td');
        var $fieldSearch = $('#step3Content #filterItems .filter-item-'  + filterID);

        methods.step3.result.updateSelectOption(filterID, fieldSetting, $default, $fieldSearch);
    }
    else
    {
        $defaultTd.empty();
        $defaultTd.html(defaultHTML);
        initDatepicker($defaultTd);

        var $fieldSearch = $('#step3Content #filterItems .filter-item-'  + filterID);
        var spanHtml     = methods.step3.result.getSpanHtml(filterID);
        $fieldSearch.html(spanHtml + defaultHTML);
        $fieldSearch.find('input').removeAttr('onchange');
        initDatepicker($fieldSearch, attrDateCheck);
    }

    var defaultValue;
    if(type == 'select') defaultValue = [];
    if(type == 'input') defaultValue = '';
    if(type == 'date' || type == 'datetime') defaultValue = { begin: '', end: '' };

    var filterParams = {type: type, default: defaultValue};
    if(refreshSaveAs)
    {
        filterParams.saveAs = ''; // 切换类型的时候，重置saveAs字段
        $(typeSelect).closest('.filter').find('#saveAs').data('zui.picker').setValue('');
    }

    DataStorage.updateFilter(filterID, filterParams);
    methods.step3.result.toggleSaveAsShow(typeSelect, type == 'select');
    calcDesignGrowFilter();
}

methods.step3.result.toggleSaveAsShow = function(obj, show = true)
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

methods.step3.result.changeSaveAs = function(saveAsSelect, fieldSaveAs)
{
    var filterID = methods.step3.result.getFilterID(saveAsSelect);
    var field    = methods.step3.result.getFilterField(saveAsSelect);
    DataStorage.updateFilter(filterID, {saveAs: fieldSaveAs});

    var fieldSettings = DataStorage.fieldSettings;
    var fieldSetting  = fieldSettings[field];
    var $default      = $(saveAsSelect).closest('.filter').find('.default-line > td');
    var $fieldSearch  = $('#step3Content #filterItems .filter-item-'  + filterID);

    methods.step3.result.updateSelectOption(filterID, fieldSetting, $default, $fieldSearch);
}

methods.step3.result.updateSelectOption = function(filterID, fieldSetting, $default, $fieldSearch)
{
    var pivot  = DataStorage.clone('pivot');
    var filter = pivot.filters[filterID];
    var saveAs = 'saveAs' in filter ? filter.saveAs : '';

    var pivotParams = {sql: $('#sql').val(), filters: pivot.filters};
    $.post(createLink('pivot', 'ajaxGetSysOptions', "type=" + fieldSetting.type + "&object=" + fieldSetting.object + "&field=" + fieldSetting.field + "&saveAs=" + saveAs), pivotParams, function(options)
    {
        if(!options) options = '<select id="default" name="default" class="form-control form-select" onchange="methods.step3.result.changeDefault(this,this.value)"></select>';
        $default.empty();
        $default.html(options);
        $default.find('#default').attr('onchange', 'methods.step3.result.changeDefault(this,this.value)');
        initPicker($default);

        var spanHtml = methods.step3.result.getSpanHtml(filterID);
        $fieldSearch.empty();
        $fieldSearch.html(spanHtml + options);
        $fieldSearch.find('#default').removeAttr('onchange');
        initPicker($fieldSearch, 'picker-select', true);
    });
}

methods.step3.result.changeDefault = function(control, value)
{
    var filterBlock = $(control).closest('.filter');
    var filterIndex = filterBlock.attr('class').replace('filter filter-', '');

    var filter = DataStorage.pivot.filters[filterIndex];

    var defaultValue = filter.default;
    var $controlItem = $('#step3Content #filterItems .filter-item-'  + filterIndex);
    if(filter.type == 'input')
    {
        defaultValue = value;
        $controlItem.find('#default').val(value);
    }
    else if(filter.type == 'select')
    {
        defaultValue = $(control).data('zui.picker').getValue();
        $controlItem.find('#default').data('zui.picker').setValue(defaultValue);
    }
    else if(filter.type == 'date' || filter.type == 'datetime')
    {
        if($(control).hasClass('default-begin'))
        {
            defaultValue.begin = value;
        }
        else
        {
            defaultValue.end = value;
        }
        var dateClass = $(control).hasClass('default-begin') ? '.default-begin' : '.default-end';
        var $dateItem = $($controlItem).find(dateClass);
        var canChange = checkDate(control, $dateItem);
        if(!canChange) return;

        $dateItem.val(value);
    }

    DataStorage.updateFilter(filterIndex, {default: defaultValue});
}
methods.step3.result.renderFilterForm = function(filter, resp, index)
{
    var $filterForm = $('#step3Content #filterForm');

    var tpl = $('#step3Content #resultFilterBlockTpl').html();

    var data =
    {
        index: index,
        filterName: filter.name,
        fieldSelect: resp[index].field,
        saveAs: resp[index].saveAs,
        saveAsClass: resp[index].saveAsClass,
        type: resp[index].type,
        defaultControl: resp[index].default
    };
    var html = $($.zui.formatString(tpl, data));
    initPicker(html);
    initDatepicker(html);
    $filterForm.append(html);
}

methods.step3.result.renderFilterItem = function(filter, resp, index, step)
{
    if(!step) step = DataStorage.step;

    var tpl = $('#step3Content #resultFilterItemTpl').html();

    var data =
    {
        index: index,
        name: filter.name,
        search: resp[index].item
    };
    var html = $($.zui.formatString(tpl, data));
    initPicker(html, 'picker-select', true);
    initDatepicker(html, attrDateCheck);
    $('#step' + step + 'Content #filterItems').append(html);
}

function renderFilters(step = 3)
{
    var pivot = DataStorage.pivot;
    var fieldSettings = DataStorage.fieldSettings;
    var langs = DataStorage.langs;

    if(pivot.filters.length == 0)
    {
        $('.filterContent').addClass('hidden');
        $('#query-tip').removeClass('hidden');
    }
    else
    {
        $('.filterContent').removeClass('hidden');
        $('#query-tip').addClass('hidden');
        $('#mainContent .clear-padding').removeClass('hidden');
    }

    if(step == 3)
    {
        var $filterForm = $('#step3Content #filterForm');
        $filterForm.empty();
    }
    $('#step' + step + 'Content #filterItems').empty();

    if(pivot.filters.length >= 1) getFilterForm(step);
}

function getFilterForm(step)
{
    var filters       = DataStorage.pivot.filters;
    var fieldSettings = DataStorage.fieldSettings;
    var langs         = DataStorage.langs;
    var func          = DataStorage.isQueryFilter() ? 'ajaxGetQueryForm' : 'ajaxGetResultForm';
    var type          = DataStorage.isQueryFilter() ? 'query' : 'result';

    if(type == 'query')
    {
        if(step == 3)
        {
            $('.create-action button').removeClass('add-filter').addClass('disabled').attr('disabled', true).attr('title', cannotAddResult);
            methods.step3[type].renderFilterForm(filters);
            var $filterForm = $('#step3Content #filterForm');
            $filterForm.find('.filter-item').css('margin-bottom', '0');
        }

        methods.step3[type].renderFilterItem(filters, step);
        return;
    }

    $.post(createLink('pivot', func),
    {
        fieldSettings: fieldSettings,
        langs: langs,
        filters: filters,
        sql: $('#sql').val()
    }, function(resp)
    {
        resp = JSON.parse(resp);

        filters.forEach(function(filter, index)
        {
            if(step == 3)
            {
                methods.step3[type].renderFilterForm(filter, resp, index);
                var $filterForm = $('#step3Content #filterForm');
                if(index + 1 == filters.length) $filterForm.find('.filter-' + index).css('margin-bottom', '0');
            }

            methods.step3[type].renderFilterItem(filter, resp, index, step);
        });

        $('#step' + step + 'Content #filterItems').append(queryDom);
        calcDesignGrowFilter();
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
    $('#step3Content #sidebar .filter-' + filterID + ' .filter-title').find('.filter-name-span').text(name);
    $('#step3Content #sidebar .filter-' + filterID + ' #name').removeClass('has-error');
    $('#step3Content #sidebar .filter-' + filterID + ' #name').siblings().remove();

    DataStorage.updateFilter(filterID, {name: name});
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
