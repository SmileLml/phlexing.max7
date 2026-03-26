$(function()
{
    $('[name^=control]').change(function()
    {
        var $tr             = $(this).parents('tr');
        var $type           = $tr.find('[name^=type]');
        var $length         = $tr.find('[name^=length]');
        var $integerDigits  = $tr.find('[name^=integerDigits]');
        var $decimalDigits  = $tr.find('[name^=decimalDigits]');
        var $optionType     = $tr.find('[name^=optionType]');
        var $default        = $tr.find('[name^=default]');
        var type            = window.defaultField.type;
        var optionClass     = window.defaultField.optionClass;
        var control         = $(this).val();
        var length          = $length.val();
        var integerDigits   = $integerDigits.val();
        var decimalDigits   = $decimalDigits.val();
        var optionType      = $optionType.val();
        var isOptionControl = (control == 'select' || control == 'multi-select' || control == 'radio' || control == 'checkbox');

        if(!length)        length        = window.defaultField.varcharLength;
        if(!integerDigits) integerDigits = window.defaultField.integerDigits;
        if(!decimalDigits) decimalDigits = window.defaultField.decimalDigits;

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

        $length.val(length);
        $integerDigits.val(integerDigits);
        $decimalDigits.val(decimalDigits);
        $type.val(type).change().find('option').show().not('.' + optionClass).hide();

        if(isOptionControl)
        {
            $optionType.val(optionType).change().removeAttr('disabled').trigger('chosen:updated');
        }
        else
        {
            $optionType.val('').change().attr('disabled', 'disabled').trigger('chosen:updated');
        }
    });

    $('[name^=type]').change(function()
    {
        var $tr  = $(this).parents('tr');
        var type = $(this).val();

        if(type == 'char')    $tr.find('[name^=length]').attr({'placeholder' : window.placeholder.charLength,    'title' : window.placeholder.charLength,    'max' : window.maxField.charLength});
        if(type == 'varchar') $tr.find('[name^=length]').attr({'placeholder' : window.placeholder.varcharLength, 'title' : window.placeholder.varcharLength, 'max' : window.maxField.varcharLength});

        $tr.find('.length').toggle(type == 'char' || type == 'varchar');
        $tr.find('.digits').toggle(type == 'decimal');

        setDefault($tr);
    });

    $('[name^=length], [name^=integerDigits], [name^=decimalDigits]').change(function()
    {
        setDefault($(this).parents('tr'));
    });

    $('[name^=optionType]').change(function()
    {
        var $tr             = $(this).parents('tr');
        var $sql            = $tr.find('[name^=sql]');
        var $options        = $tr.find('[name^=options]');
        var optionType      = $(this).val();
        var control         = $tr.find('[name^=control]').val();
        var isOptionControl = (control == 'select' || control == 'multi-select' || control == 'radio' || control == 'checkbox');

        $sql.parent().find('.text-error').remove();

        if(isOptionControl && optionType == 'sql')
        {
             $sql.show();
             $options.val('');
             $options.hide();
        }
        else if(isOptionControl && optionType == 'custom')
        {
            $sql.val('');
            $sql.hide();
            $options.removeAttr('readonly').show();
        }
        else
        {
            $sql.val('');
            $sql.hide();
            $options.attr('readonly', 'readonly').show();
        }

        setDefault($tr);
    });

    $('[name^=options], [name^=sql]').change(function()
    {
        $(this).parent().find('.text-error').remove();
        setDefault($(this).parents('tr'));
    });

    $('#fieldTable [name^=control]').change();
    $('#fieldTable button#submit').click(function(){ setTimeout(function(){$('#fieldTable').resize()}, 100); });
})

function setDefault($tr)
{
    var key        = $tr.data('key');
    var control    = $tr.find('[name^=control]').val();
    var type       = $tr.find('[name^=type]').val();
    var optionType = $tr.find('[name^=optionType]').val();
    var $defaultTD = $tr.find('[name^=default]').parent();

    if(control == 'textarea' || control == 'multi-select' || control == 'checkbox' || control == 'richtext' || control == 'file')
    {
        $defaultTD.html("<input type='text' name='default[" + key + "]' id='default" + key + "' class='form-control' readonly>");
        return false;
    }
    if(control == 'input')
    {
        $defaultTD.html("<input type='text' name='default[" + key + "]' id='default" + key + "' class='form-control' autocomplete='off'>");
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

        $defaultTD.html("<input type='text' name='default[" + key + "]' id='default" + key + "' class='form-control form-date' autocomplete='off'>");
        $tr.find('[name^=default]').datetimepicker(dateOptions);
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

        $defaultTD.html("<input type='text' name='default[" + key + "]' id='default" + key + "' class='form-control form-datetime' autocomplete='off'>");
        $tr.find('[name^=default]').datetimepicker(datetimeOptions);
        return false;
    }
    if(control == 'integer')
    {
        var max = window.maxField[type];
        var min = window.minField[type];

        $defaultTD.html("<input type='number' name='default[" + key + "]' id='default" + key + "' max='" + max + "' min='" + min + "' step='1' class='form-control' autocomplete='off'>");
        return false;
    }
    if(control == 'decimal' || control == 'formula')
    {
        var integerDigits = $tr.find('[name^=integerDigits]').val();
            integerDigits = integerDigits ? parseInt(integerDigits) : 0;
            integerDigits = integerDigits > window.maxField.integerDigits ? window.maxField.integerDigits     : integerDigits;
            integerDigits = integerDigits < window.minField.integerDigits ? window.defaultField.integerDigits : integerDigits;
            integerDigits = parseInt(integerDigits);

        var decimalDigits = $tr.find('[name^=decimalDigits]').val();
            decimalDigits = decimalDigits ? parseInt(decimalDigits) : 0;
            decimalDigits = decimalDigits > window.maxField.decimalDigits ? window.maxField.decimalDigits     : decimalDigits;
            decimalDigits = decimalDigits < window.minField.integerDigits ? window.defaultField.decimalDigits : decimalDigits;
            decimalDigits = parseInt(decimalDigits);

        var max  = '.'.padStart(integerDigits + 1, 9).padEnd(integerDigits + decimalDigits + 1, 9);
        var min  = '-' + max;
        var step = '0.'.padEnd(decimalDigits + 1, 0) + 1;

        $defaultTD.html("<input type='number' name='default[" + key + "]' id='default" + key + "' max='" + max + "' min='" + min + "' step='" + step + "' class='form-control' autocomplete='off'>");
        return false;
    }

    if(!optionType || optionType == 'category' || optionType == 'prevModule')
    {
        $defaultTD.html("<input type='text' name='default[" + key + "]' id='default" + key + "' class='form-control' autocomplete='off'>");
        return false;
    }

    var name         = (control == 'multi-select' || control == 'checkbox') ? 'default[' + key + '][]' : 'default[]';
    var defaultValue = $tr.find('[name^=default]').val();
    if(typeof defaultValue === 'string') defaultValue = defaultValue.split(',');

    if(optionType == 'custom')
    {
        var multiple = (control == 'multi-select' || control == 'checkbox') ? 'multiple' : '';

        $defaultTD.html("<select name='" + name + "' id='default" + key + "' class='form-control'" + multiple + '>');
        $defaultTD.find('[name^=default]').append("<option></option>");

        var options = $tr.find('[name^=options]').val();
        $.each(options.split('\n'), function(index, value)
        {
            var arr = value.split(',');
            var code = arr[0] ? arr[0] : '';
            var name = arr[1] ? arr[0] : '';
            $defaultTD.find('[name^=default]').append("<option value='" + code + "'>" + name + '</option>');
        })

        $defaultTD.find('[name^=default]').val(defaultValue).chosen();
    }
    else
    {
        var type = $tr.find('[name^=type]').val();
        var sql  = $tr.find('[name^=sql]').val();

        control = window.btoa(encodeURI(control));
        sql     = window.btoa(encodeURI(sql));
        name    = window.btoa(encodeURI(name));
        value   = window.btoa(encodeURI(defaultValue));

        var link = createLink('workflowfield', 'ajaxGetDefaultControl', 'mode=advanced&control=' + control + '&optionType=' + optionType + '&type=' + type + '&sql=' + sql + '&sqlVars=&elementName=' + name + '&default=' + value);
        $defaultTD.load(link, function()
        {
            $defaultTD.find('[name^=default]').val(defaultValue);
            initDefault($defaultTD.find('[name^=default]'), control, optionType, type, sql);
        })
    }

    return false;
}
