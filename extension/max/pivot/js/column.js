$(function()
{
    $(document).on('click', '.add-column', addColumn);
});

function renderColumns()
{
    var form = $('#step2Content').find('form#columnForm');
    form.empty();

    var pivot         = DataStorage.clone('pivot');
    var fieldSettings = DataStorage.fieldSettings;
    var langs         = DataStorage.langs;
    if(!pivot.settings.columns) pivot.settings.columns = [{field: '', stat: '', slice: 'noSlice', showMode: 'default', monopolize: '' , showTotal: 'noShow'}];
    DataStorage.pivot = pivot;

    $.post(createLink('pivot', 'ajaxGetColumnForm', 'pivotID=' + pivot.id), {fieldSettings: fieldSettings, columns: pivot.settings.columns, langs: langs}, function(resp)
    {
        resp = JSON.parse(resp);
        pivot.settings.columns.forEach(function(column, index)
        {
            form.append(columnRow(resp, index));
        });

        setDeleteColumnHidden(form, pivot.settings.columns.length);
        $('#columnTotal').data('zui.picker').setValue(pivot.settings.columnTotal);
    });
}

function columnRow(resp, index)
{
    var tpl  = $('#step2Content').find('#columnTpl').html();
    var showOrigin = resp[index].showOrigin;
    var data =
    {
        index: index,
        columnIndex:    resp[index].columnIndex,
        fieldSelect:    resp[index].fieldSelect,
        sliceField:     resp[index].sliceField,
        calcMode:       resp[index].calcMode,
        showMode:       resp[index].showMode,
        monopolize:     resp[index].monopolize,
        monopolizeHide: resp[index].monopolizeHide,
        showTotal:      resp[index].showTotal,
        fieldHide:      resp[index].fieldHide,
        columnHidden:   showOrigin ? 'hidden' : '',
        maskHidden:     showOrigin ? '' : 'hidden'
    };
    var html = $($.zui.formatString(tpl, data))
    initPicker(html);
    html.find('#column').next().css('width', '180');
    html.find('.showOrigin input#showOrigin0').prop('checked', showOrigin);

    return html;
}

/**
 * Judge whether the element can be deleted in #columnForm.
 *
 * @param  object form
 * @param  int    length
 * @access public
 * @return void
 */
function setDeleteColumnHidden(form, length)
{
    if(length == 1)
    {
        form.find('.column-delete').addClass('hidden');
    }
    else
    {
        form.find('.column-delete').removeClass('hidden');
    }
}


/**
 * Add a column.
 *
 * @access public
 * @return void
 */
function addColumn()
{
    var column = {field: '', stat: '', slice: 'noSlice', showMode: 'default', showTotal: 'noShow'};
    var pivot  = DataStorage.clone('pivot');
    pivot.settings.columns.push(column);
    DataStorage.pivot = pivot;

    renderColumns();
}

/**
 * Remove a column.
 *
 * @param  int index
 * @access public
 * @return void
 */
function removeColumn(index)
{
    bootbox.confirm(pivotLang.deleteColumn, function(res)
    {
        if(res)
        {
             var pivot = DataStorage.clone('pivot');
             pivot.settings.columns.splice(index, 1);
             DataStorage.pivot = pivot;
             renderColumns();
        }
    });
}
