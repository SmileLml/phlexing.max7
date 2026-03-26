<?php js::set('edition', $config->edition);?>
<?php $fieldList = array();?>
<div id='mainContent' class='main-content'>
  <div class="display-content">
    <div class="cell">
      <div class='panel panel-position panel-padding'>
        <div class='panel-heading'>
          <div class='panel-title ptitle'>
            <?php echo $pivot->name;?>
            <?php if(common::hasPriv('pivot', 'export')):?>
              <a href="#" class="btn btn-link design-export pull-right hidden" data-toggle="modal" data-target="#export"><?php echo '<i class="icon-export muted"> </i>' . $lang->export;?></a>
            <?php endif;?>
          </div>
        </div>
        <div class="panel-body">
          <div id="datagridSpanExample2" class="datagrid">
            <div class='panel-body clear-padding clear-overflow'>
              <div id='filterContent' class='filterContent'>
                <div id="filterItems">
                </div>
                <div id='queryButton2' class='queryButton'>
                  <button type="button" onclick='queryFilter()' class="btn btn-secondary save-step"><?php echo $lang->pivot->query;?></button>
                </div>
              </div>
            </div>
          </div>
          <div id='datagrid-tip' class="panel-position-child">
            <p><span class="text-muted"><?php echo $lang->pivot->noPivotTip;?></span></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="config-content" id="sidebar">
    <div class="cell">
      <div class='panel panel-settings'>
        <div class='panel-heading border-bottom'>
          <div class='panel-title ptitle'>
            <?php echo $lang->pivot->baseSetting;?>
          </div>
        </div>

        <div class="panel-content">
          <div class='panel-heading border-bottom'>
            <div class='group-title'>
              <div class='panel-title' style="display:flex;">
                <div class="checkbox-primary">
                  <input type="checkbox" name="summary[]" id="summary" onclick='changeSummary()'; title="<?php echo $lang->pivot->step2->summary;?>">
                  <label for="summary"><span class='panel-title'><?php echo $lang->pivot->step2->summary;?></span>
                  </label>
                </div>
                <div style="padding-left: 3px;">
                  <i class="icon icon-help group-tip" data-toggle="popover" data-trigger="focus hover" data-placement="right" data-tip-class="text-muted popover-sm" data-content="<?php echo $lang->pivot->step2->summaryTip;?>" data-delay="100"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="panel-content auto-scroll" id='summaryForm'>
          <div class='panel-heading border-bottom'>
            <div class='group-title'>
              <div class='panel-title'>
                <?php echo $lang->pivot->step2->group;?>
                <i class="icon icon-help group-tip" data-toggle="popover" data-trigger="focus hover" data-placement="right" data-tip-class="text-muted popover-sm" data-content="<?php echo $lang->pivot->step2->groupsTip;?>" data-delay="100"></i>
              </div>
            </div>
            <div class='panel-body'>
              <form class='form-ajax' id="groupForm">
                <table class='table table-form'>
                </table>
              </form>
            </div>
          </div>

          <div class='panel-heading border-bottom' style='padding-bottom: 0px !important;'>
            <div class='panel-flex'>
              <div class='panel-title'>
                <?php echo $lang->pivot->step2->column;?>
                <i class="icon icon-help group-tip" data-toggle="popover" data-trigger="focus hover" data-placement="right" data-tip-class="text-muted popover-sm" data-content="<?php echo $lang->pivot->step2->columnsTip;?>" data-delay="100"></i>
              </div>
              <div class="pull-right column-flex column-center">
                <?php echo '<a class="add-column btn btn-link"><i class="icon icon-plus"></i> ' . $lang->pivot->addColumn . '</a>';?>
              </div>
            </div>
            <div class='panel-body'>
              <form class='form-ajax' id="columnForm"></form>
            </div>
          </div>

          <div class='panel-heading'>
            <div class='panel-title'>
              <?php echo $lang->pivot->step2->columnTotal;?>
              <i class="icon icon-help group-tip" data-toggle="popover" data-trigger="focus hover" data-placement="right" data-tip-class="text-muted popover-sm" data-content="<?php echo $lang->pivot->step2->columnTotalTip;?>" data-delay="100"></i>
            </div>
            <div class='panel-body top-padding'>
              <form class='form-ajax' id="columnTotalForm">
                <table class='table table-form'>
                  <tr>
                    <td class='group-width'>
                      <?php echo html::select('columnTotal', $lang->pivot->step2->columnTotalList, isset($pivot->settings['columnTotal']) ? $pivot->settings['columnTotal'] : 'noShow', "class='form-control picker-select'");?>
                    </td>
                  </tr>
                </table>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class='panel pull-bottom'>
        <div class='panel-heading'>
          <div class='panel-footer'>
            <?php echo '<button type="button" class="btn btn-secondary btn-save-setting" onclick="apply()">' . $lang->pivot->saveSetting . '</button>';?>
            <?php echo '<button type="button" class="btn btn-primary btn-next-step" onclick="nextStep()">' . $lang->pivot->nextStep . '</button>';?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<template id='fieldSelectTpl'>
</template>

<template id='statSelectTpl'>
<?php echo html::select('statTpl', $lang->pivot->step2->statList, '', "class='form-control picker-select' data-placeholder='{$lang->pivot->step2->selectStat}'");?>
</template>

<template id="columnTpl">
  <div class='column column-{index}'>
    <div class="column-header">
      <div>
        <div class='column-title title-flex'>
          <div class='column-heading column-flex column-center column-padding-right'>
            <span>{columnIndex}</span>
          </div>
          <div>
            <div class="input-group">
              {fieldSelect}
              <span class="input-group-addon required showOrigin">
              <?php echo html::checkBox('showOrigin', $lang->pivot->showOriginItem);?>
              </span>
            </div>
          </div>
          <div class="column-flex column-delete" style="margin-left:auto;">
            <button type="button" class="remove-column close" onclick="removeColumn({index})"><i class='icon icon-minus'></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="column-body">
      <form class="not-watch {columnHidden}">
        <table class="table table-form table-margin">
          <tbody>
            <tr>
              <th class='column-width'><?php echo $lang->pivot->sliceField;?></th>
              <td>{sliceField}</td>
            </tr>
            <tr>
              <th class='column-width'><?php echo $lang->pivot->calcMode;?></th>
              <td>{calcMode}</td>
            </tr>
            <tr>
              <th><?php echo $lang->pivot->showMode;?></th>
              <td>
                <div class='input-group'>
                {showMode}
                <span class='input-group-addon {monopolizeHide}'>
                  {monopolize}
                </span>
                </div>
              </td>
            </tr>
            <tr class='default-line {fieldHide}'>
              <th><?php echo $lang->pivot->showTotal;?></th>
              <td>{showTotal}</td>
            </tr>
          </tbody>
        </table>
      </form>
      <form class="mask {maskHidden}">
        <table class="table table-form table-margin">
          <tbody>
            <tr>
              <th class='column-width'><?php echo $lang->pivot->sliceField;?></th>
              <td><?php echo html::select('columnMask', array('' => ''), '', "class='form-control picker-select disabled' data-placeholder='{$this->lang->pivot->showOriginPlaceholder->slice}' disabled");?></td>
            </tr>
            <tr>
              <th class='column-width'><?php echo $lang->pivot->calcMode;?></th>
              <td><?php echo html::select('calcModeMask', array('' => ''), '', "class='form-control picker-select disabled' data-placeholder='{$this->lang->pivot->showOriginPlaceholder->calcMode}' disabled");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->pivot->showMode;?></th>
              <td><?php echo html::select('showModeMask', array('' => ''), '', "class='form-control picker-select disabled' data-placeholder='{$this->lang->pivot->showOriginPlaceholder->showMode}' disabled");?></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</template>

<template id='groupActionTpl'>
  <td class='text-left btn-list'>
    <a href='javascript:;' onclick='addGroup(this)' class='btn btn-link btn-add' title='<?php echo $lang->pivot->step2->add;?>'><i class='icon-plus'></i></a>
    <a href='javascript:;' onclick='deleteGroup(this)' class='btn btn-link btn-delete' title='<?php echo $lang->pivot->step2->delete;?>'><i class='icon-close'></i></a>
  </td>
</template>

<template id='queryFilterItemTpl'>
  <div class='filter-item filter-item-{index} input-group' data-index='{index}'>
    <span class='field-name input-group-addon'>{name}</span>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-input '")?></div>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-date '")?></div>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-datetime '")?></div>
    <div class="default-block hidden"><?php echo html::select('default', '', '', "class='form-control form-select multiple'");?></div>
  </div>
</template>

<?php js::set('groupNum', $lang->pivot->step2->groupNum);?>
<?php js::set('groupTip', $lang->pivot->step2->groupTip);?>
<script>
$(document).ready(function()
{
    $(document).on('change', '#groupForm td select, #columnForm .column select,  #columnForm .column input[type=checkbox], #columnTotalForm td select', function(evt)
    {
        var id = $(evt.target).attr('id');
        var value = $(evt.target).val();

        var refreshIds = ['column', 'stat', 'slice', 'showMode', 'monopolize1', 'showTotal', 'showOrigin0'];

        if(refreshIds.includes(id))
        {
            refreshColumnSetting();
        }
        else if(id == 'columnTotal')
        {
            var pivot = DataStorage.clone('pivot');
            pivot.settings.columnTotal = value;
            DataStorage.pivot = pivot;
        }
        else
        {
            // render groups
            var pivot = DataStorage.clone('pivot');

            for(var i = 1; i <= 5; i++)
            {
                var groupID = 'group' + i;
                delete pivot.settings[groupID];
            }
            $('#groupForm').find('table tr select').each(function(index)
            {
                var groupID = 'group' + (index + 1);
                pivot.settings[groupID] = $(this).val();
            });

            DataStorage.pivot = pivot;

            updateGroupField();
        }
    });

    $(document).on('change', '#columnForm .column .showOrigin input[type=checkbox]', function(evt)
    {
        var checked = $(evt.target).prop('checked');
        var $columnBody = $(evt.target).closest('.column-header').next('.column-body');
        var $mask = $columnBody.find('.mask');
        var $form = $columnBody.find('.not-watch');
        if(checked)
        {
            $mask.removeClass('hidden');
            $form.addClass('hidden');
        }
        else
        {
            $mask.addClass('hidden');
            $form.removeClass('hidden');
        }
    });

    $('.group-tip').popover();
});

function refreshColumnSetting()
{
    var content = $('#step2Content');
    var form    = content.find('form#columnForm');

    var columns = [];
    form.find('.column').each(function(index, elem)
    {
        var column     = $(elem).find('select#column').val();
        var stat       = $(elem).find('select#stat').val();
        var slice      = $(elem).find('select#slice').val();
        var showMode   = $(elem).find('select#showMode').val();
        var monopolize = $(elem).find('input#monopolize1').prop('checked') ? '1' : '0';
        var showTotal  = $(elem).find('select#showTotal').val();
        var showOrigin = $(elem).find('input#showOrigin0').prop('checked') ? '1' : '0';
        $(elem).find('select#showTotal').closest('tr').toggle(slice !== 'noSlice');
        if(showMode === 'default')
        {
            $(elem).find('input#monopolize1').closest('span').addClass('hidden');
        }
        else
        {
            $(elem).find('input#monopolize1').closest('span').removeClass('hidden');
        }
        columns.push({field: column, stat, slice, showMode, monopolize, showTotal, showOrigin});
    });
    var pivot = DataStorage.clone('pivot');
    pivot.settings.columns = columns;
    DataStorage.pivot = pivot;
}

function apply(showError = true)
{
    if(validate(showError)) showTable();
}

function showTable()
{
    setSearchFilters();
    var pivot = DataStorage.clone('pivot');
    var step  = DataStorage.step;
    var langs = DataStorage.langs;
    var pivotParams  = JSON.parse(JSON.stringify(pivot));
    pivotParams.step = step;
    pivotParams.langs = langs;

    $.post(createLink('pivot', 'ajaxGetPivot'), pivotParams,function(resp)
    {
        var myDatagrid = $('#datagridSpanExample' + step);
        myDatagrid.find('.reportData').remove();
        myDatagrid.append(resp);
    });
    $('.design-export').removeClass('hidden');

    /* Just use once. */
    delete pivot.searchFilters;
    DataStorage.pivot = pivot;
}

function addGroup(evt)
{
    var tr    = $(evt).parent().parent();
    var form  = $(evt).closest('form');
    var rows  = 1;
    form.find('table tr select').each(function()
    {
        rows = Math.max(rows, parseInt($(this).attr('id').substring('5')));
    });

    var newRow = groupRow(rows + 1);
    tr.after(newRow);

    initPicker(newRow);
    updateGroupField();
    setDeleteGroupHidden(form);
    setAddHidden(form);
}

function deleteGroup(evt)
{
    var form = $(evt).closest('form');
    var id   = $(evt).closest('tr').find('select').attr('id');

    $(evt).parent().parent().remove();

    updateGroupField();
    setDeleteGroupHidden(form);
    setAddHidden(form);

    var pivot = DataStorage.clone('pivot');
    delete(pivot.settings[id]);
    DataStorage.pivot = pivot;
}

function deleteColumn(evt)
{
    var form = $(evt).closest('form');
    $(evt).parent().parent().remove();
    setDeleteGroupHidden(form);
    refreshColumnSetting();
}

function setDeleteGroupHidden(form)
{
    var rows = form.find('table tr').length;
    if(rows == 1)
    {
        form.find('table tr .btn-delete').addClass('hidden');
    }
    else
    {
        form.find('table tr .btn-delete').removeClass('hidden');
    }
}

function setAddHidden(form)
{
    var maxRows = edition == 'max' ? 5 : 3;
    var rows = form.find('table tr').length;
    if(rows == maxRows && form.attr('id') == 'groupForm')
    {
        form.find('table tr .btn-add').addClass('hidden');
    }
    else
    {
        form.find('table tr .btn-add').removeClass('hidden');
    }
}

function initGroupForm()
{
    var content = $('#step2Content');
    var form    = content.find('form#groupForm');
    form.find('table').empty();

    var hasGroup = false;
    $.each(DataStorage.pivot.settings, function(index, value)
    {
        if(index.indexOf('group') != '-1')
        {
            index = index.substring('5');
            form.find('table').append(groupRow(index));
            hasGroup = true;
        }
    });
    if(!hasGroup) form.find('table').append(groupRow(1));

    initPicker(form);
    updateGroupField();
    setDeleteGroupHidden(form);
    setAddHidden(form);
}

function groupRow(index)
{
    var name       = 'group' + index;
    var value      = DataStorage.pivot.settings['group' + index];
    var select     = fieldSelect(name, value, groupTip)
    var content    = $('#step2Content');
    var actionHtml = content.find('#groupActionTpl').html();

    var td = $('<td class="group-width"></td>').append(select);
    var tr = $('<tr></tr>').append(td);
    tr.append(actionHtml);

    return tr;
}

function fieldSelect(name, value = '', placeholder = '', required = false)
{
    var content   = $('#step2Content');
    var fieldHtml = content.find('#fieldSelectTpl').html();

    var field = $(fieldHtml);
    field.attr('name', name);
    field.attr('id', name.replace('[', '').replace(']', ''));
    field.attr('data-placeholder', placeholder);
    field.val(value);

    if(required) field.addClass('required')

    return field;
}

function statSelect(name, value = '', placeholder = '')
{
    var content   = $('#step2Content');
    var statHtml   = content.find('#statSelectTpl').html();

    var stat = $(statHtml);
    stat.attr('name', name);
    stat.attr('id', name.replace('[', '').replace(']', ''));
    stat.attr('data-placeholder', placeholder);
    stat.val(value);

    return stat;
}

function updateGroupField()
{
    $('#groupForm td select').each(function()
    {
        var groupPicker = $(this).data('zui.picker');
        var optionList  = groupPicker.list;

        optionList.forEach(function(option){ option.disabled = false });
        groupPicker.updateOptionList(optionList);
    })

    $('#groupForm td select').each(function()
    {
        var $select      = $(this);
        var selectPicker = $select.data('zui.picker');
        if(!selectPicker) return;

        var selectItem = selectPicker.getListItem(selectPicker.getValue());
        if(!selectItem) return;

        var groupName = $(this).attr('name');
        $('#groupForm td select').each(function()
        {
            var currentGroup = $(this).attr('name');
            if(currentGroup == groupName) return;

            var $currentPicker = $(this).data('zui.picker');
            $currentPicker.updateOptionList([$.extend({}, selectItem, {disabled: true})]);
        });
    })
}
</script>
<script><?php include('../../js/column.js');?></script>
