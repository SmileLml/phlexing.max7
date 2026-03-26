<div id='mainContent' class='main-row'>
  <div class='main-col'>
    <style><?php include $this->app->getModuleRoot() . 'dataview/css/querybase.css';?></style>
    <?php include $this->app->getModuleRoot() . 'dataview/view/querybase.html.php';?>
    <script><?php include $this->app->getModuleRoot() . 'dataview/js/querybase.js';?></script>
  </div>
</div>
<template id='queryFilterItemTpl'>
  <div class='filter-item filter-item-{index} input-group' data-index='{index}'>
    <span class='field-name input-group-addon'>{name}</span>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-input '")?></div>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-date'")?></div>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-datetime'")?></div>
    <div class="default-block hidden"><?php echo html::select('default', '', '', "class='form-control form-select'");?></div>
  </div>
</template>

<div id='queryFilterModal' class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">× </button>
        <h4 class="modal-title"><?php echo $lang->dataview->add . $lang->dataview->queryFilters?></h4>
      </div>
      <div class="modal-body">
          <div class="alert alert-info no-margin"><?php echo $lang->dataview->queryFilterTip;?></div>
          <div class="form-overflow">
            <table class='table table-form'>
              <thead>
                <tr class='text-center'>
                  <th class='w-160px text-left require'><?php echo $lang->dataview->varFilter->varCode?></th>
                  <th class='w-160px text-left require'><?php echo $lang->dataview->varFilter->varLabel?></th>
                  <th><?php echo $lang->dataview->varFilter->requestType?></th>
                  <th class='text-left' style="width: 142px;"><?php echo $lang->dataview->varFilter->default?></th>
                  <th class='text-left' style="width: 30px;"></th>
                </tr>
              </thead>
              <tbody class='text-center'></tbody>
            </table>
          </div>
          <table class='table table-form'>
            <tfoot>
              <tr>
                <td colspan='4' class='text-center'><?php echo html::submitButton();?></td></tr>
            </tfoot>
          </table>
      </div>
    </div>
  </div>
</div>

<template id="queryFilterFormTpl">
  <tr class="filter-item form-filter-{index}" data-index='{index}'>
    <td><?php echo html::input('varName', '{varName}', "class='form-control required' {disabled} placeholder='{$lang->pivot->varNameTip}' onchange='handleVarNameChange(this)'")?></td>
    <td><?php echo html::input('showName', '{showName}', "class='form-control required' onchange='handleShowNameChange(this)'")?></td>
    <td>
      <div class='input-group request-type'>
        <span class='input-group-addon' style='text-align:left'>
        <?php echo html::radio('requestType[{index}]', $lang->dataview->varFilter->requestTypeList, '{requestType}', "onchange='methods.step1.query.toggleRadio(this)'")?>
        </span>
        <?php echo html::select('selectList', $lang->dataview->varFilter->selectList, '{select}', "class='form-control {hidden}' onchange='methods.step1.query.toggleSelect(this, this.value)'");?>
      </div>
    </td>
    <td>
      <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-input '")?></div>
      <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-date '")?></div>
      <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-datetime '")?></div>
      <div class="default-block hidden"><?php echo html::select('default', '', '', "class='form-control form-select'");?></div>
    </td>
    <td>
       <a href="javascript:;" onclick="methods.step1.query.deleteRow(this)" class="btn btn-link hidden"><i class="icon icon-trash"></i></a>
    </td>
  </tr>
</template>

<template id="nextStepTpl">
  <button type="button" class="btn btn-primary btn-next-step pull-right" onclick="nextStep()"><?php echo $lang->pivot->nextStep;?></button>
</template>

<template id="addQueryFilterTpl">
  <button type="button" class="btn add-query-filter" style="margin-left: 8px;"><?php echo $lang->dataview->add . $lang->dataview->queryFilters;?></button>
</template>

<template id="queryFiltersTpl">
  <tr id="queryFilters" class="hidden">
    <th></th>
    <td></td>
  </tr>
</template>

<script>
DataStorage.addProperty('isInsert', true);
var queryFilterModal   = '#step1Content #queryFilterModal';
var queryFilters       = '#step1Content #queryFilters';
var queryFilterFormTpl = '#step1Content #queryFilterFormTpl';

$(function()
{
    $('#dataform .btn.query').after([$('#addQueryFilterTpl').html(), $('#nextStepTpl').html()]);
    $('#dataform .btn.query').parent().parent().before($('#queryFiltersTpl').html());

    $('.add-query-filter').click(function()
    {
        DataStorage.isInsert = true;
        methods.step1.query.renderFilterForm([{}]);
    });

    $(queryFilterModal + ' #submit').click(function()
    {
        var pivot = DataStorage.clone('pivot');
        var sql = $('#sql').val();
        pivot.filters = [];
        var filters = methods.step1.query.getFilterFormData();

        if(!filters) return;

        pivot.filters = filters;
        if(DataStorage.isInsert) $('#sql').val(sql + " $" + filters[filters.length - 1].field);

        pivot.settings.filterType = 'query';
        DataStorage.pivot = pivot;
        $(queryFilterModal).modal('hide');
        methods.step1.query.renderFilters();
    })
    methods.step1.query.renderFilters();
});

function handleVarNameChange(elem)
{
    var $td = $(elem).parent('td');
    $td.removeClass('has-error');
    if($td.find('#varNameError').length > 0) $td.find('#varNameError').remove();
}

function handleShowNameChange(elem)
{
    var $td = $(elem).parent('td');
    $td.removeClass('has-error');
    if($td.find('#showNameError').length > 0) $td.find('#showNameError').remove();
}

methods.step1.query.getFilterFormData = function()
{
    var hasError = false;
    var filters  = [];
    $(queryFilterModal + ' .table tbody tr').each(function()
    {
        $(this).find('td').removeClass('has-error');
        var varName      = $(this).find("input[name^='varName']").val();
        var showName     = $(this).find("input[name^='showName']").val();
        var selectList   = $(this).find("select[name^='selectList']").val();
        var requestType  = $(this).find("input[name^='requestType']:checked").val();

        var defaultType  = ".form-" + requestType;
        var defaultValue = requestType == 'select' ? $(this).find("select[name^='default']" + defaultType).data('zui.picker').getValue() : $(this).find("input[name^='default']" + defaultType).val();

        if($(this).find('#varNameError').length > 0) $(this).find('#varNameError').remove();
        if($(this).find('#showNameError').length > 0) $(this).find('#showNameError').remove();

        if(!varName || varName.length == 0)
        {
            hasError = true;
            $(this).find("input[name^='varName']").parent().addClass('has-error').append('<div id="varNameError" class="text-danger text-left">' + notemptyLang.replace('%s', '<?php echo $lang->dataview->varFilter->varCode?>') + '</div>');
        }
        if(!showName || showName.length == 0)
        {
            hasError = true;
            $(this).find("input[name^='showName']").parent().addClass('has-error').append('<div id="showNameError" class="text-danger text-left">' + notemptyLang.replace('%s', '<?php echo $lang->dataview->varFilter->varLabel?>') + '</div>');
        }

        var filter = {from: 'query', field: varName, type: requestType, typeOption: selectList, name: showName, default: defaultValue};
        filters.push(filter);
    });

    return hasError ? false : filters;
}

methods.step1.query.renderFilters = function()
{
    var pivot = DataStorage.pivot;
    $(queryFilters).removeClass('hidden');
    $(queryFilters + ' td').empty();

    if(!DataStorage.isQueryFilter())
    {
        $(queryFilters).addClass('hidden');
        return;
    }

    var filters = pivot.filters;

    filters.forEach(function(filter, index)
    {
        var tpl = $(queryFilterItemTpl).html();
        var data =
        {
            index: index,
            name: filter.name,
        };
        var html = $($.zui.formatString(tpl, data));
        var control = methods.step1.query.getControl(filter.type, html);

        $(queryFilters + ' td').append(html);
        methods.step1.query.initControl(control, filter.type, filter.field, filter.default, filter.typeOption);
    })
}

methods.step1.query.getVarListFromSql = function()
{
    var sql     = $('#sql').val();
    var pattern = /\$\w+/g;

    var varList = sql.match(pattern);

    if(Array.isArray(varList)) varList = Array.from(new Set(varList));
    if(!Array.isArray(varList)) varList = [];

    return varList;
}

/**
 * Set varFrom.
 *
 * @access public
 * @return void
 */
function setVarFrom()
{
    var pivot = DataStorage.pivot;
    var filters = pivot.filters;

    if(sql !== '')
    {
        var varList = methods.step1.query.getVarListFromSql();

        if(methods.step1.query.needEditQuery())
        {
            var dataList = [];
            varList.forEach(function(varStr, index)
            {
                varStr = varStr.substr(1);
                var find = filters.find(function(filter){return filter.field == varStr});

                if(!find) dataList.push({varName: varStr, disabled: 'disabled'});
            });

            methods.step1.query.renderFilterForm(dataList);
            methods.step1.query.updateFilters(varList);
            return false;
        }

        if(varList && varList.length < filters.length && DataStorage.isQueryFilter())
        {
            methods.step1.query.updateFilters(varList);
            methods.step1.query.renderFilters();
        }
    }

    return true;
}

/**
 * Update filters form sql variables to storage.
 *
 * @param array varList
 * @access public
 * @return void
 */
methods.step1.query.updateFilters = function(varList)
{
    var newFilters = [];
    var pivot = DataStorage.clone('pivot');
    varList.forEach(function(varStr)
    {
        var find = pivot.filters.find(function(filter){return filter.field == varStr.substr(1)});

        if(find) newFilters.push(find);
    });

    pivot.filters = newFilters;
    DataStorage.pivot = pivot;
}

/**
 * Need edit query filter or not
 *
 * @access public
 * @return bool
 */
methods.step1.query.needEditQuery = function()
{
    var varList = methods.step1.query.getVarListFromSql();
    var pivot   = DataStorage.clone('pivot');

    /* If the sql has not any variable for query filter. */
    if(!varList)
    {
        if(pivot.settings.filterType == 'query')
        {
            pivot.filters = [];
            pivot.settings.filterType = '';
            DataStorage.pivot = pivot;
        }

        return false;
    }

    /* Find if add new variables to sql. */
    var newVars = varList.filter(function(varStr)
    {
        var findInFilters = pivot.filters.find(function(filter){return filter.field == varStr.substr(1);});
        return !findInFilters;
    });

    return newVars.length > 0;
}

/**
 * ToggleSelectList.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
methods.step1.query.toggleRadio = function(obj)
{
    $(obj).parents('td').find('select').addClass('hidden');
    var radioValue = $(obj).val();
    if(radioValue == 'select')
    {
        $(obj).parents('td').find('select').removeClass('hidden');
    }

    var defaultControl = $(obj).parents('tr').find('.default-control');
    var control = methods.step1.query.getControl(radioValue, $(obj).parents('tr'));
    methods.step1.query.initControl(control, radioValue, 'default', '', 'user');
}

methods.step1.query.toggleSelect = function(obj, options)
{
    var type = 'select';
    var defaultControl = $(obj).parents('tr').find('.default-control');
    var control = methods.step1.query.getControl(type, $(obj).parents('tr'));
    methods.step1.query.initControl(control, type, 'default', '', options);
}

methods.step1.query.renderFilterForm = function(dataList, showModal = true)
{
    var tplDataList = [];
    var varList = methods.step1.query.getVarListFromSql();
    var filters = DataStorage.pivot.filters;

    varList.forEach(function(varStr, index)
    {
        varStr = varStr.substr(1);
        var find = filters.find(function(filter){return filter.field == varStr});
        if(!find) return;

        var data = {disabled: 'disabled', varName: varStr};

        if(find)
        {
            data.showName     = find.name;
            data.defaultValue = find.default;
            data.requestType  = find.type;
            data.select       = find.typeOption
        }

        tplDataList.push(data);
    });

    tplDataList = tplDataList.concat(dataList);

    var tbody = $(queryFilterModal + ' .table.table-form tbody');
    tbody.empty();
    tplDataList.forEach(function(tplData, index)
    {
        methods.step1.query.renderFilterItem(tbody, tplData, index);
    });
    if(tplDataList.length >= 2) tbody.find("tr:last-child td:last-child").find('a').removeClass('hidden');

    if(showModal) $(queryFilterModal).modal('show');
}

methods.step1.query.renderFilterItem = function(node, data, index)
{
    var tplData = {index: index, disabled: '', varName: '', showName: '', requestType: 'input', select: 'user', defaultValue: ''};
    for(var key in tplData)
    {
        if(data.hasOwnProperty(key)) tplData[key] = data[key];
    }
    tplData.hidden = tplData.requestType == 'select' ? '' : 'hidden';

    var type         = tplData.requestType;
    var select       = tplData.select;
    var defaultValue = tplData.defaultValue;

    var tpl  = $(queryFilterFormTpl).html();
    var html = $($.zui.formatString(tpl, tplData));

    node.append(html);
    html.find('input[name^="requestType"][value="' + type + '"]').attr('checked', 'checked');

    if(type == 'select') html.find('#selectList').val(select);

    var control = methods.step1.query.getControl(type, html);
    methods.step1.query.initControl(control, type, 'default', defaultValue, select);
}

methods.step1.query.deleteRow = function(trashObj)
{
    $(trashObj).parents('tr').remove();
}

methods.step1.query.getControl = function(type, html)
{
    html.find('.default-block').addClass('hidden');
    html.find('.default-block input[name="default"]').val('');
    var control = html.find('.form-' + type);
    control.parent('.default-block').removeClass('hidden');
    return control;
}

methods.step1.query.initControl = function(control, type, name, value, options)
{
    if(type == 'date')     setDateField(control);
    if(type == 'datetime') setDateField(control);
    if(type == 'select')
    {
        var picker = control.data('zui.picker');
        if(picker) picker.destroy();

        $.post(createLink('pivot', 'ajaxGetSysOptions', 'type=' + options), function(resp)
        {
            control.html($(resp).html());
            control.picker({maxDropHeight: pickerHeight});
            control.data('zui.picker').setValue(value);
        });
    }
}
</script>
