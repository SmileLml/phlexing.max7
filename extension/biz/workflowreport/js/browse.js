$(document).ready(function()
{
		/* Set drag event for save report sort. */
    $('#reportList').on('sort.sortable', function(e, data)
    {
        $.post(createLink('workflowreport', 'sort'), data.orders, function(response)
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

    /* Click title to load chart. */
    $('#reportList tr').find("td:not(':first,:last')").click(function()
    {
        preview($(this).parent().data('id'));
    });

    if(window.reportSize == 0)
    {
        $('.existReport').hide();
        $('.noReport').show();
    }
    else
    {
        if(window.id)
        {
            preview(window.id);
        }
        else
        {
            $('#reportList tr:first td:eq(1)').click();
        }
    }

    $(document).on('change', "input[name='type'][type='radio']", function()
    {
        toggleSelect();
    });

    $(document).on('change', "input[name='countType'][type='radio']", function()
    {
        var countType = $("input[name='countType'][type='radio']:checked").val();
        $('#fieldsSelect').parent().parent().toggle(countType != 'count');
    });

    /* Display fields of current module when dimension selected. */
    $(document).on('change', '#dimension', function()
    {
        var dimension = $("#dimension").val();
        var module = dimension.substr(0, dimension.indexOf('_'));
        /* Display fields where belong to dimension's sub module and main table module if dimension is sub module. */
        if(module == window.moduleName)
        {
            $('#fields option').show();
        }
        else
        {
            $('#fields option').hide();

            $('#fields option[value^=' + window.moduleName + '_]').show();
            $('#fields option[value^=' + module + '_]').show();

            /* String is pie fields,object is line or bar fields. */
            fieldValue = $("#fields").val();
            if(typeof fieldValue == 'string')
            {
                if(fieldValue.indexOf(module + '_') != 0 && fieldValue.indexOf(window.moduleName + '_') != 0) $("#fields").val("");
            }
            else
            {
                var fieldsVal = [];
                $.each(fieldValue, function(i, val)
                {
                    if(val.indexOf(window.moduleName + '_') == 0 || val.indexOf(module + '_') == 0) fieldsVal.push(val);
                });
                $("#fields").val(fieldsVal)
            }
        }
        $('#fields').trigger('chosen:updated');

        /* Display granularity if dimension control is date or datetime. */
        var isDate = window.controlPairs[dimension] == 'date' || window.controlPairs[dimension] == 'datetime';
        $('#granularity_chosen').toggle(isDate);
        $('#dimension_chosen a input').toggleClass('dimension-half-radius', isDate);
        $('#dimension_chosen a').toggleClass('dimension-all-radius dimension-half-radius');
        isDate ? $('#granularity').removeAttr("disabled") : $('#granularity').attr("disabled","disabled");
    });
});

/* Toggle which select to display. */
function toggleSelect()
{
    var type = $("input[name='type'][type='radio']:checked").val();
    if(type == 'pie')
    {
        $('#fields').removeAttr('multiple');
    }
    else
    {
        $('#fields').attr('multiple', 'multiple');
    }
    $('#fields').trigger('chosen:updated');
}

/* Load preview chart. */
function preview(id)
{
    let url = createLink('workflowreport', 'ajaxPreview', 'id=' + id);
    $('.existReport').load(url, function()
    {
        $('#reportList tr').removeClass('checked row-check-begin row-check-end');
        $('#reportList tr[data-id=' + id + ']').addClass('checked row-check-begin row-check-end');
    });
}
