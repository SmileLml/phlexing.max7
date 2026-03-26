$(document).ready(function()
{
    window.minField.integerDigits = parseInt(window.minField.integerDigits);
    window.maxField.integerDigits = parseInt(window.maxField.integerDigits);
    window.minField.decimalDigits = parseInt(window.minField.decimalDigits);
    window.maxField.decimalDigits = parseInt(window.maxField.decimalDigits);

    $('#fieldList').on('sort.sortable', function(e, data)
    {
        $.post(createLink('workflowfield', 'sort'), data.orders, function(response)
        {
            if(response.result == 'success')
            {
                return location.reload();
            }
            else
            {
                bootbox.alert(response.message);
            }
        }, 'json');
    });

    $(document).off('click', '.deleteField');
    $('.deleteField').click(function()
    {
        let $deleter = $(this);
        let confirmDeleteLang = defaultConfirmDelete;
        if(quotedFields.includes($deleter.data('field'))) confirmDeleteLang = confirmDeleteHasQuote;
        if($deleter.data('role') == 'quote') confirmDeleteLang = confirmDeleteInQuote;
        if(confirm(confirmDeleteLang))
        {
            $.getJSON($deleter.attr('href'), function(response)
            {
                if(response.result == 'success') return location.reload();
                return bootbox.alert({message:response.message, size: "small"});
            });
        }
        return false;
    });

    /* Toggle options. */
    $(document).on('change', '#control', function()
    {
        var control = $(this).val();
        $('#default, #rules, #type, #optionType, #sql, #optionsDIV, .dataTip, .set-expression').parents('tr').toggle(control != 'file');

        var isHiddenPlaceholder = hiddenPlaceholder != undefined ? !hiddenPlaceholder.includes(control) : false;
        $('.tipInfoBox').toggle(isHiddenPlaceholder);

        if(control == 'file') return false;

        var type            = window.defaultField.type;
        var optionClass     = window.defaultField.optionClass;
        var length          = $('#length').val();
        var integerDigits   = parseInt($('#integerDigits').val());
        var decimalDigits   = parseInt($('#decimalDigits').val());
        var isOptionControl = (control == 'select' || control == 'multi-select' || control == 'radio' || control == 'checkbox');

        if(!length) length = window.defaultField.varcharLength;

        if(!integerDigits || integerDigits < window.minField.integerDigits || integerDigits > window.maxField.integerDigits) integerDigits = window.defaultField.integerDigits;
        if(!decimalDigits || decimalDigits < window.minField.decimalDigits || decimalDigits > window.maxField.decimalDigits) decimalDigits = window.defaultField.decimalDigits;

        switch(control)
        {
            case 'formula' :
            case 'decimal' :
                type        = 'decimal';
                optionClass = 'decimal';
                length      = '';
                break;
            case 'integer' :
                type        = 'int';
                optionClass = 'integer';
                length      = '';
                break;
            case 'multi-select' :
            case 'checkbox' :
            case 'textarea' :
            case 'richtext' :
                type        = 'text';
                optionClass = 'text';
                length      = '';
                break;
            case 'date' :
                type        = 'date';
                optionClass = 'date';
                length      = '';
                break;
            case 'datetime' :
                type        = 'datetime';
                optionClass = 'time';
                length      = '';
                break;
        }

        $('#length').val(length);
        $('#integerDigits').val(integerDigits);
        $('#decimalDigits').val(decimalDigits);
        $('#type option').show().not('.' + optionClass).hide();
        $('#type').val(type).change();

        $('.set-expression').parents('tr').toggle(control == 'formula');
        $('#optionType').change().parents('tr').toggle(isOptionControl);
    });

    $(document).on('change', '#type', function()
    {
        var type = $(this).val();

        if(type == 'char')    $('#length').attr({'placeholder' : window.placeholder.charLength,    'title' : window.placeholder.charLength,    'max' : window.maxField.charLength});
        if(type == 'varchar') $('#length').attr({'placeholder' : window.placeholder.varcharLength, 'title' : window.placeholder.varcharLength, 'max' : window.maxField.varcharLength});

        $('.length').toggle(type == 'char' || type == 'varchar');
        $('.digits').toggle(type == 'decimal');
        $('#default').parents('tr').toggle(type != 'text');

        setDataTip();
        setDefaultControl();
    });

    $(document).on('change', '#length, #integerDigits, #decimalDigits', function()
    {
        setDataTip();
        setDefaultControl();
    });

    $(document).on('change', '#optionType', function()
    {
        var control         = $('#control').val();
        var isOptionControl = (control == 'select' || control == 'multi-select' || control == 'radio' || control == 'checkbox');

        $('#optionTR').toggle(isOptionControl && $(this).val() == 'custom');
        $('#optionTR .input-group').fixInputGroup();
        $('.sqlTR').toggle(isOptionControl && $(this).val() == 'sql');
        $('#varsTR').toggle(isOptionControl && $(this).val() == 'sql' && $.trim($('#varsTD').html()) != '');

        $('#optionsDIVLabel, #sqlLabel').remove();
        $('#sql').css({'border-color' : '', 'margin-bottom' : 0});

        setDefaultControl();
    });

    $(document).on('change', 'input[name^=options], #sql', function()
    {
        setDefaultControl();
    });

    /* Add a option. */
    $(document).on('click', '.addItem', function()
    {
        var $parent = $(this).parents('.input-group');
        $parent.after($parent.prop('outerHTML').replace('checked="checked"', ''));
        $parent.next().find('input[type=text]').val('');
    });

    /* Delete a option. */
    $(document).on('click', '.delItem', function()
    {
        if($('#optionTR .subStatusTd').length > 0)
        {
            var $sortTd  = $(this).closest('.sortTd');
            var rowCount = $sortTd.find('.input-group').length;
            if(rowCount == 2) $sortTd.find('.sort-btn').addClass('hidden');
        }

        if($(this).closest('td').find('div.input-group').size() == 1)
        {
            $(this).parents('.input-group').find('input').val('');
        }
        else
        {
            $(this).parents('.input-group').remove();
        }
    });

    $(document).on('change', '[name^=optionCode]', function()
    {
        $(this).parent('.input-group').find('[name^=optionDefault]').val($(this).val());
    });

    $(document).on('click', '[name=requestType]', function()
    {
        $('#selectList').toggle($(this).val() == 'select' || $(this).val() == 'multi-select');
    });

    $(document).on('click', '.delSqlVar', function()
    {
        $('#sql').val($('#sql').val().replace("'$" + $(this).parents('.varControl').attr('id') + "'", ''));
        $(this).parents('.varControl').remove();
        fixVarControls();
    });

    $(document).on('click', '.btn-expression', function()
    {
        var text = $(this).html();
        var data = $(this).data();
        var type = $(this).data('type');

        $('#expressionDIV .expression').append("<span class='item-expression item-" + type + "'>" + text + "</span>");

        window.expression.push(data);
        removeErrorLabel();
    });

    $(document).on('click', '.clear-last', function()
    {
        $('#expressionDIV .expression .item-expression:last').remove();

        window.expression.pop();
        removeErrorLabel();
    });

    $(document).on('click', '.clear-all', function()
    {
        $('#expressionDIV .expression .item-expression').remove();

        window.expression.length = 0;
        removeErrorLabel();
    });

    $(document).on('click', '.save-expression', function()
    {
        var hasError = checkExpression();
        if(!hasError)
        {
            $('#fieldTable .expression').html($('#expressionDIV .expression').html());
            $('#fieldTable #expression').val(JSON.stringify(window.expression));
            $('#expressionDIV').hide();
            $('#fieldTable').show();
        }
    });

    $(document).on('click', '.cancel-expression', function()
    {
        removeErrorLabel();

        $('#expressionDIV').hide();
        $('#fieldTable').show();
    });

    $(document).on('click', '.set-expression', function()
    {
        initExpression();

        $('#expressionLabel').remove();
        $('#expressionDIV').show();
        $('#fieldTable').hide();
    });

    $panelHeadingHeight = $('.panel-heading').outerHeight(true);
    $panelMarginBottom  = $('.panel').css('margin-bottom').replace('px', '');
    $editorNavHeight    = $('#editorNav').outerHeight(true);
    $editorMenuHeight   = $('#editorMenu').outerHeight();
    $spaceHeight        = $('.space.space-sm').outerHeight(true);

    $maxHeight = $(window).height() - $panelHeadingHeight - $panelMarginBottom - $editorNavHeight - $editorMenuHeight - $spaceHeight;
    $('.panel-body').css('max-height', $maxHeight + 'px');
});

function setDataTip()
{
    var type     = $('#type').val();
    var $dataTip = $('.dataTip');

    switch(type)
    {
        case 'tinyint' :
        case 'smallint' :
        case 'mediumint' :
        case 'int' :
            var max = window.maxField[type];
            var min = window.minField[type];
            $dataTip.html(window.tips.number.replace(/MAX/, max).replace(/MIN/, min)).parents('tr').show();
            break;
        case 'decimal' :
            var integerDigits = $('#integerDigits').val();
                integerDigits = integerDigits ? parseInt(integerDigits) : 0;
                integerDigits = integerDigits > window.maxField.integerDigits ? window.maxField.integerDigits     : integerDigits;
                integerDigits = integerDigits < window.minField.integerDigits ? window.defaultField.integerDigits : integerDigits;
                integerDigits = parseInt(integerDigits);

            var decimalDigits = $('#decimalDigits').val();
                decimalDigits = decimalDigits ? parseInt(decimalDigits) : 0;
                decimalDigits = decimalDigits > window.maxField.decimalDigits ? window.maxField.decimalDigits     : decimalDigits;
                decimalDigits = decimalDigits < window.minField.integerDigits ? window.defaultField.decimalDigits : decimalDigits;
                decimalDigits = parseInt(decimalDigits);

            var max = '.'.padStart(integerDigits + 1, 9).padEnd(integerDigits + decimalDigits + 1, 9);
            var min = '-' + max;

            $dataTip.html(window.tips.number.replace(/MAX/, max).replace(/MIN/, min)).parents('tr').show();
            break;
        case 'char' :
            var length = $('#length').val();
                length = length ? parseInt(length) : 0;
                length = length > window.maxField.charLength ? window.maxField.charLength     : length;
                length = length < window.minField.charLength ? window.defaultField.charLength : length;

            $dataTip.html(window.tips.string.replace(/LENGTH/, length)).parents('tr').show();
            break;
        case 'varchar' :
            var length = $('#length').val();
                length = length ? parseInt(length) : 0;
                length = length > window.maxField.varcharLength ? window.maxField.varcharLength     : length;
                length = length < window.minField.varcharLength ? window.defaultField.varcharLength : length;

            $dataTip.html(window.tips.string.replace(/LENGTH/, length)).parents('tr').show();
            break;
        default :
            $('#length').val('');
            $('#integerDigits').val('');
            $('#decimalDigits').val('');
            $dataTip.html('').parents('tr').hide();
    }
}

function setDefaultControl()
{
    var control      = $('#control').val();
    var type         = $('#type').val();
    var optionType   = $('#optionType').val();
    var defaultValue = $('#default').val();

    if(control == 'input' || control == 'textarea' || control == 'multi-select' || control == 'checkbox' || control == 'richtext' || control == 'file')
    {
        $('#default').parent().html("<input type='text' name='default' value='" + defaultValue + "' id='default' class='form-control' autocomplete='off'>");
        return false;
    }
    if(control == 'date')
    {
        var dateOptions =
        {
            language:  config.clientLang,
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            format: 'yyyy-mm-dd'
        };

        $('#default').parent().html("<input type='text' name='default' value='" + defaultValue + "' id='default' class='form-control form-date' autocomplete='off'>");
        $('#default').datetimepicker(dateOptions);
        return false;
    }
    if(control == 'datetime')
    {
        var datetimeOptions =
        {
            language:  config.clientLang,
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
            format: 'yyyy-mm-dd hh:ii'
        };

        $('#default').parent().html("<input type='text' name='default' value='" + defaultValue + "' id='default' class='form-control form-datetime' autocomplete='off'>");
        $('#default').datetimepicker(datetimeOptions);
        return false;
    }
    if(control == 'integer')
    {
        var type = $('#type').val();
        var max  = window.maxField[type];
        var min  = window.minField[type];

        $('#default').parent().html("<input type='number' name='default' value='" + defaultValue + "' id='default' max='" + max + "' min='" + min + "' step='1' class='form-control' autocomplete='off'>");
        return false;
    }
    if(control == 'decimal' || control == 'formula')
    {
        var integerDigits = $('#integerDigits').val();
            integerDigits = integerDigits ? parseInt(integerDigits) : 0;
            integerDigits = integerDigits > window.maxField.integerDigits ? window.maxField.integerDigits     : integerDigits;
            integerDigits = integerDigits < window.minField.integerDigits ? window.defaultField.integerDigits : integerDigits;
            integerDigits = parseInt(integerDigits);

        var decimalDigits = $('#decimalDigits').val();
            decimalDigits = decimalDigits ? parseInt(decimalDigits) : 0;
            decimalDigits = decimalDigits > window.maxField.decimalDigits ? window.maxField.decimalDigits     : decimalDigits;
            decimalDigits = decimalDigits < window.minField.integerDigits ? window.defaultField.decimalDigits : decimalDigits;
            decimalDigits = parseInt(decimalDigits);

        var max  = '.'.padStart(integerDigits + 1, 9).padEnd(integerDigits + decimalDigits + 1, 9);
        var min  = '-' + max;
        var step = '0.'.padEnd(decimalDigits + 1, 0) + 1;

        $('#default').parent().html("<input type='number' name='default' value='" + defaultValue + "' id='default' max='" + max + "' min='" + min + "' step='" + step + "' class='form-control' autocomplete='off'>");
        return false;
    }

    if(!optionType || optionType == 'category' || optionType == 'prevModule')
    {
        $('#default').parent().html("<input type='text' name='default' value='" + defaultValue + "' id='default' class='form-control' autocomplete='off'>");
        return false;
    }

    if(typeof defaultValue === 'string') defaultValue = defaultValue.split(',');

    if(optionType == 'custom')
    {
        var name     = (control == 'multi-select' || control == 'checkbox') ? 'default[]' : 'default';
        var multiple = (control == 'multi-select' || control == 'checkbox') ? 'multiple' : '';

        $('#default').parent().html("<select name='" + name + "' id='default' class='form-control'" + multiple + '>');
        $('#default').append("<option></option>");

        $('input[id^=options][id*=code]').each(function(index, code)
        {
            var code = $(this).val();
            var name = $(this).closest('.input-group').find('input[id^=options][id*=name]').val();

            $('#default').append("<option value='" + code + "'>" + name + '</option>');
        });

        $('#default').val(defaultValue).chosen();
    }
    else
    {
        var type = $('#type').val();
        var sql  = $('#sql').val();

        control = window.btoa(encodeURI(control));
        sql     = window.btoa(encodeURI(sql));
        value   = window.btoa(encodeURI(defaultValue));

        var link = createLink('workflowfield', 'ajaxGetDefaultControl', 'mode=advanced&control=' + control + '&optionType=' + optionType + '&type=' + type + '&sql=&sqlVar=&elementName=&default=' + value);
        $.post(link, {sql: sql}, function(data)
        {
            $('#default').parent().html(data);
            initDefault($('#default'), control, optionType, type, sql);
        })
    }

    return false;
}

function initDefault($selector, control, optionType, type, sql)
{
    if($selector.find('option[value=ajax_search_more]').length == 1)
    {
        $selector.find('option[value=ajax_search_more]').remove();

        var url = createLink('workflowfield', 'ajaxGetMoreDefault', 'mode=advanced&control=' + control + '&optionType=' + optionType + '&type=' + type + '&sql=' + sql + '&sqlVars=&search={search}');

        initPicker($selector, {remote: url});
    }
    else
    {
        $selector.chosen();
    }
}

function fixVarControls()
{
    var varControls = $('#varsTD .varControl');
    if(varControls.size() == 0) $('#varsTR').hide();
    for(i = 0; i < varControls.size(); i++)
    {
        if(i % 2 == 0)
        {
            $(varControls[i]).removeClass('pull-left pull-right').addClass('pull-left');
        }
        else
        {
            $(varControls[i]).removeClass('pull-left pull-right').addClass('pull-right');
        }
        if(i > 1)
        {
            $(varControls[i]).css('padding-top', '5px');
        }
        else
        {
            $(varControls[i]).css('padding', '0');
        }
    }
}

function initExpression()
{
    let name        = $('#name').val() == '' ? window.formulaLang.common : $('#name').val();
    let $expression = $('#expressionDIV .expression');

    $expression.find('.item-name').html(name);
    $expression.find('.item-expression').remove();

    if($('#expression').val())
    {
        window.expression = JSON.parse($('#expression').val());

        appendExpression(window.expression, $expression);
    }
    else
    {
        window.expression.length = 0;
    }
}

function appendExpression(expression, $selector)
{
    for(var i in expression)
    {
        let current = expression[i];
        let text    = current.text;
        if(current.type == 'target')
        {
            if(current.function)
            {
                text = window.formulaLang.functions[current.function].replace('%s', window.modules[current.module]).replace('%s', window.moduleFields[current.module][current.field]);
            }
            else
            {
                text = window.modules[current.module] + '_' + window.moduleFields[current.module][current.field];
            }
        }

        $selector.append("<span class='item-expression item-" + current.type + "'>" + text + "</span>");
    }
}

function checkExpression()
{
    if(window.expression.length == 0)
    {
        appendErrorLabel(window.formulaLang.error.empty);

        return true;
    }
    else
    {
        let fakeExpression = [];
        for(var i in window.expression)
        {
            let current = window.expression[i];

            if(current.type == 'target')   fakeExpression.push(current.field);
            if(current.type == 'operator') fakeExpression.push(current.operator);
            if(current.type == 'number')   fakeExpression.push(current.value);
        }

        fakeExpression = fakeExpression.join('');
        try
        {
            math.parse(fakeExpression);
        }
        catch(error)
        {
            appendErrorLabel(window.formulaLang.error.error);

            return true;
        }

        let error  = false;
        let length = window.expression.length;
        for(var i in window.expression)
        {
            i = parseInt(i);

            let current = window.expression[i];
            let prev    = '';
            let next    = '';

            if(i > 0)         prev = window.expression[i - 1];
            if(i < length -1) next = window.expression[i + 1];

            switch(current.type)
            {
                case 'target' :
                    if(prev != '' && (prev.type != 'operator' || prev.operator == ')'))
                    {
                        error = true;
                        break;
                    }
                    if(next != '' && (next.type != 'operator' || next.operator == '('))
                    {
                        error = true;
                        break;
                    }
                    break;
                case 'number' :
                    if(current.value == '.')
                    {
                        if(prev == '' || prev.type != 'number' || prev.value == '.')
                        {
                            error = true;
                            break;
                        }
                        if(next == '' || next.type != 'number' || next.value == '.')
                        {
                            error = true;
                            break;
                        }
                    }
                    else
                    {
                        if(prev != '' && (prev.type == 'target' || (prev.type == 'operator' && prev.operator == ')')))
                        {
                            error = true;
                            break;
                        }
                        if(next != '' && (next.type == 'target' || (next.type == 'operator' && next.operator == '(')))
                        {
                            error = true;
                            break;
                        }
                    }
                    break;
                case 'operator' :
                    switch(current.operator)
                    {
                        case '(' :
                            if(prev != '' && (prev.type != 'operator' || prev.operator == ')'))
                            {
                                error = true;
                                break;
                            }
                            if(next == '' || (next.type == 'number' && next.value == '.') || (next.type == 'operator' && next.operator != '('))
                            {
                                error = true;
                                break;
                            }
                            break;
                        case ')' :
                            if(prev == '' || (prev.type == 'number' && prev.value == '.') || (prev.type == 'operator' && prev.operator != ')'))
                            {
                                error = true;
                                break;
                            }
                            if(next != '' && (next.type != 'operator' || next.operator == '('))
                            {
                                error = true;
                                break;
                            }
                            break;
                        default :
                            if(prev == '' || (prev.type == 'operaor' && prev.operator != ')') || (prev.type == 'number' && prev.value == '.'))
                            {
                                error = true;
                                break;
                            }
                            if(next == '' || (next.type == 'operaor' && next.operator != '(') || (next.type == 'number' && next.value == '.'))
                            {
                                error = true;
                                break;
                            }
                    }
                    break;
            }

            if(error)
            {
                appendErrorLabel(window.formulaLang.error.error);

                return true;
            }
        }

        return false;
    }
}

function appendErrorLabel(message)
{
    removeErrorLabel();
    $('#expressionDIV .expression').css('border-color', '#953B39').after("<span id='expressionLabel' for='expression' class='text-error red'>" + message + '</span>');
    $('.fieldForm').parents('.modal-body').scrollTop(0);
}

function removeErrorLabel()
{
    $('#expressionDIV .expression').css('border-color', '').next('#expressionLabel').remove();
}
