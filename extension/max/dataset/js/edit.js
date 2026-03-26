var rows = [];
var modal;
if(Array.isArray(fieldSettings))  fieldSettings  = {};
if(Array.isArray(allTypeOptions)) allTypeOptions = {};

function query(callback) {
    $.post(createLink('dataset', 'ajaxQuery'), {sql: $('#sql').val()}, function(resp) {
        resp = JSON.parse(resp);
        if(resp.result !== 'success')
        {
            $('.error').removeClass('hidden');
            if(typeof resp.message.errorInfo != 'undefined')
            {
                $('.error td').html(resp.message.errorInfo.join(' '));
            }
            else
            {
                $('.error td').html(resp.message);
            }
        }
        else
        {
            fields = resp.fields;
            rows   = resp.rows;
            vars   = resp.vars;
            drawTable(resp.fields, resp.rows);
            if(typeof callback !== 'undefined') callback();
        }
    });
}

$(document).ready(function()
{
    $('#sql').on('input', function() { $('.error').addClass('hidden'); })
    $('.query').click(function() {
        query();
    })

    $('.fieldSettings').click(function() {
        query(function() {
            modal = new $.zui.ModalTrigger({title: lang.fieldSettings, custom: function(el) {
                var html = '<form id="edit" onsubmit="javascript:saveSettings();return false;">';
                html += '<table class="table table-form">';
                html += '<thead><tr><th class="w-100px">' + lang.field + '</th><th class="w-250px">' + lang.fieldName + '</th><th class="w-300px">' + lang.fieldType + '</th></tr></thead><tbody>';
                for(let index in fields)
                {
                    var field = fields[index];
                    var typeOptions = genTypeOptions(index);
                    var name = typeof fieldSettings[field] == 'undefined' ? '' : fieldSettings[field].name;
                    html += '<tr><td>' + field + '</td><td><input name="name" id="name' + index + '" class="form-control" value="' + name + '" /></td><td class="td-row">' + typeOptions + '</td></tr>';
                }
                html += '</tbody>';

                html += '<tfoot><tr><td colspan="3" class="text-center"><button type="submit" id="submit" class="btn btn-wide btn-primary">' + lang.save + '</button></td></tr></tfoot>';
                html += '</table>';
                html += '</form>';

                return html;
            }});
            modal.show({onShow: function()
            {
                setTimeout(function() {
                    $('.chosen').chosen();
                    $('select[name^="object"]').change(function(e)
                    {
                        var object = $(this).val();
                        var objectid = $(this).attr('id');
                        var $that = $(this);
                        if(!object) return false;

                        $.get(createLink('dataset', 'ajaxGetTypeOptions', 'object=' + object), function(res)
                        {
                            var index = objectid.substring(6);
                            var id = 'type' + index;

                            var res = JSON.parse(res);
                            var options = '';
                            for(let key in res.options)
                            {
                                options += '<option value="' + key + '">' + res.options[key].name + '</option>';
                            }
                            allTypeOptions[object] = res.options;

                            var html = '<select class="form-control chosen" name="type[]" id="' + id + '">'+ options + '</select>';

                            $that.closest('.td-row').find('.typebox').html(html);
                            $('.types').chosen();
                        });
                    });
                }, 20)

                return false;
            }})
            modal.show();
        });
    })

    $('.save').click(function() {
        query(function() {
            for(let field in fields)
            {
                if(typeof(fieldSettings[field]) == 'undefined')
                {
                    fieldSettings[field] = {
                        name: fields[field],
                        object: '',
                        field: '',
                        type: 'string',
                    };
                }
            }
            /* Fix bug #26716. */
            for(let index in fieldSettings)
            {
                if(!Object.keys(fields).includes(index)) delete fieldSettings[index];
            }

            $.post(createLink('dataset', 'edit', "dataset=" + dataset.id), {name: $('#name').val(), sql: $('#sql').val(), fields: JSON.stringify(fieldSettings), objects: JSON.stringify(allTypeOptions)}, function(res) {
                res = JSON.parse(res);
                if(res.result == 'fail')
                {
                    $('.error').removeClass('hidden');
                    if(typeof res.message.name != 'undefined') $('.error td').html(res.message.name);
                    return;
                }

                window.location.href = createLink('dataset', 'browse', 'type=custom');
            })
        })
    })
});

function saveSettings()
{
    for(let index in fields)
    {
        var object = $('#object' + index).val();
        var type = object ? allTypeOptions[object][$('#type' + index).val()].type : 'string';
        fieldSettings[fields[index]] = {
            name: $('#name' + index).val(),
            object: object,
            field: $('#type' + index).val(),
            type: type,
        }
    }

    modal.close();
    this.drawTable(fields, rows);
    return false;
}

function genTypeOptions(index)
{
    var object = typeof fieldSettings[fields[index]] == 'undefined' ? '' : fieldSettings[fields[index]].object;
    var field  = typeof fieldSettings[fields[index]] == 'undefined' ? '' : fieldSettings[fields[index]].field;

    var objectOptions = '<option value=""></option>';
    for(let value in lang.objects)
    {
        objectOptions += '<option value="' + value + '" ' + (value === object ? 'selected' : '') + '>' + lang.objects[value] + '</option>';
    }

    var typeOptions = '<option value=""></option>';
    for(let value in allTypeOptions[object])
    {
        typeOptions += '<option value="' + value + '" ' + (value === field ? 'selected' : '') + '>' + allTypeOptions[object][value].name + '</option>';
    }
    return '<div class="table-col"><select name="object[]" id="object' + index + '" class="form-control chosen">' + objectOptions + '</select></div><div class="table-col typebox"><select class="form-control types chosen" name="type[]" id="type' + index + '">' + typeOptions + '</select></div>';
}

function drawTable(fields, rows)
{
    var head = '<tr>';
    for(let field in fields)
    {
        var fieldName = fields[field];
        head += '<th>' + (typeof fieldSettings[field] !== 'undefined' && fieldSettings[field].name ? fieldSettings[field].name : fieldName) + '</th>';
    }
    head += '</tr>';

    var html = '<thead>' + head + '</thead>';

    var body = '';
    for(let row of rows)
    {
        body += '<tr>';
        for(let index in row) body += '<td>' + row[index] + '</td>';
        body += '</tr>';
    }

    html += '<tbody>' + body + '</tbody>';

    $('table.result').html(html);
}
