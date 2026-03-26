$(function()
{
    $('.example-text-holder').each(function()
    {
        $(this).attr('data-size', Math.floor(Math.random() * 8) + 2);
    });

    $('#labelList .select-label').click(function()
    {
        var labelID = $(this).parents('tr').data('id');

        selectLabels(labelID);
    });

    $('#labelList').on('sort.sortable', function(e, data)
    {
        $.post(createLink('workflowlabel', 'sort'), data.orders, function(response)
        {
            if(response.result != 'success'){bootbox.alert(response.message);}

            var orders = [];
            for(var i in data.orders) orders[data.orders[i]] = i;
            sortLabels(orders);

            var labelID = data.element.data('id');
            selectLabels(labelID);
        }, 'json');
    });

    $(document).on('change', '[name^=fields], [name^=operators]', function()
    {
        var $tr      = $(this).parents('tr');
        var $td      = $tr.find('td.value');
        var $value   = $tr.find('[name^=values]:first');
        var field    = $tr.find('[name^=field]').val();
        var operator = $tr.find('[name^=operators]').val();
        var value2   = $tr.find('[name^=values2]:first').val();

        var key   = $tr.data('key');
        var value = window.btoa(encodeURI($value.val()));
        var name  = window.btoa(encodeURI('values[' + key + ']'));

        $.get(createLink('workflowfield', 'ajaxGetFieldControl', 'module=' + window.moduleName + '&field=' + field + '&value=' + value + '&elementName=' + name), function(response)
        {
            var values = response;

            if(operator == 'between')
            {
                var values2 = response.replace(/values/g, 'values2');
                $td.empty().html("<div class='input-group'>" + values + "<span class='input-group-addon values2'></span>" + values2 + '</div>');
                $td.find('[name^=values2]:first').val(value2);
            }
            else
            {
                name = $td.find('[name^=values]:first').attr('name').replace(/values/g, 'values2');
                $td.empty().html(values + "<input type='hidden' name='" + name + "'>");
            }

            $td.find('.picker-select').each(function()
            {
                initSelect($(this));
            });

            $td.find('select.chosen').chosen();
            $td.find('.form-datetime').datetimepicker(
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
            });
            $td.find('.form-date').datetimepicker(
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
            });

            $td.find('.input-group').fixInputGroup();
        });
    });

    $(document).on('change', '[name^=orderFields]', function()
    {
        processOrderFields();
    });

    $(document).on('click', '.addItem', function()
    {
        var $tr  = $(this).parents('tr');
        var type = $(this).parent().data('type');

        $tr.after(window[type + 'Row'].replace(/KEY/g, window.key));
        $tr.next().find('.chosen').chosen();
        $tr.next().find('.values2').hide();
        $tr.next().find('.input-group').fixInputGroup();

        processOrderFields();

        window.key++;
    })

    $(document).on('click', '.delItem', function()
    {
        $(this).parents('tr').remove();

        processOrderFields();
    })

    $panelHeadingHeight = $('.panel-heading').outerHeight(true);
    $panelMarginBottom  = $('.panel').css('margin-bottom').replace('px', '');
    $editorNavHeight    = $('#editorNav').outerHeight(true);
    $editorMenuHeight   = $('#editorMenu').outerHeight();
    $spaceHeight        = $('.space.space-sm').outerHeight(true);
    
    $maxHeight = $(window).height() - $panelHeadingHeight - $panelMarginBottom - $editorNavHeight - $editorMenuHeight - $spaceHeight;
    $('.panel-body').css('max-height', $maxHeight + 'px');

    $(document).on('click', '.alert > .remove', function(){$.get(createLink('workflowlabel', 'removeFeatureTips'));});
});

function sortLabels(orders)
{
    for(var i in orders)
    {
        $('.preview-content .menu .nav').append($('li[data-id=' + orders[i] + ']'));
    }
}

function selectLabels(labelID)
{
    $('#labelList tr.active').removeClass('active');
    $('#labelList tr[data-id=' + labelID + ']').addClass('active');

    $('.preview-content .menu .nav li').removeClass('active');
    $('.preview-content .menu .nav li[data-id=' + labelID + ']').addClass('active');
}

function processOrderFields()
{
    $('[name^=orderFields] option').show();

    $('[name^=orderFields]').each(function()
    {
        var field = $(this).val();
        
        if(field)
        {
            $('[name^=orderFields]').not(this).find('option[value=' + field + ']').hide();
            $('[name^=orderFields]').not(this).trigger('chosen:updated');
        }
    });
}
