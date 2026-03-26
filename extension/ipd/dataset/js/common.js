function drawTable(fields, rows)
{
    var head = '<tr>';
    for(let field in fields)
    {
        var fieldName = fields[field];
        fieldName = (typeof fieldSettings[field] !== 'undefined' && fieldSettings[field].name ? fieldSettings[field].name : fieldName);
        var width = getTextWidth(fieldName) + 40;

        head += '<th class="header" style="min-width:' + width + 'px;">' +
            fieldName +
            '<a class="btn btn-link header-config" data-toggle="dropdown" onClick="configHeader(this);"><i class="icon icon-cog-outline"></i></a>' +
            '</th>';
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

function getTextWidth(text)
{
    var font = "bold 12pt arial"
    var canvas = document.createElement("canvas");
    var context = canvas.getContext("2d");
    context.font = font;
    var measure = context.measureText(text);

    return measure.width;
}

function configHeader(obj)
{
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
                if(!object)
                {
                    return false;
                }

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
}
