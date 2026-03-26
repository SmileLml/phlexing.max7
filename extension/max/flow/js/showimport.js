$(function()
{
    $('[rows=3]').attr('rows', 1);

    $(document).on('change', 'input,select,textarea,radio,checkbox', function()
    {
        $(this).css('border-color', '');
        $(this).next('.text-error.red').remove();
    });

    var updatePicker = function($select, picker, search, options)
    {
        value = '';
        $.each(options, function(_, option)
        {
            if(search == option.text) value = option.value;
        });
        picker.setList(options);
        picker.updateList();
        if(value) picker.setValue(value);
    }

    var optionList = {};
    $('select').each(function()
    {
        var $select = $(this);
        if(!$select.val())
        {
            var picker = $select.data('zui.picker');
            if(picker)
            {
                var module = $select.data('module');
                var field  = $select.data('field');
                var search = $select.data('value');
                var key    = md5(module + '_' + field + '_' + search);
                
                if(optionList[key])
                {
                    options = optionList[key];

                    updatePicker($select, picker, search, options);
                }
                else
                {
                    var url = createLink('flow', 'ajaxGetPairs', 'module=' + module + '&field=' + field);

                    $.ajaxSettings.async = false;
                    $.post(url, {search: search}, function(options)
                    {
                        options = JSON.parse(options);
                        optionList[key] = options;

                        updatePicker($select, picker, search, options);
                    });
                    $.ajaxSettings.async = true;
                }
            }
        }
    });

    $('#importTable').datatable();
})
