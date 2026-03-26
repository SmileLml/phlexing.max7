$(document).ready(function()
{
    $(document).on('change', '#type', function()
    {
        $('.optionTR').toggle($(this).val() == 'option');
        $('.systemTR').toggle($(this).val() == 'system');
        $('.sqlTR').toggle($(this).val() == 'sql');
        $('.langTR').toggle($(this).val() == 'lang');
    });

    $(document).on('change', '#app', function()
    {
        if($(this).val()) 
        { 
            $('.methodDesc').hide();
            $('#method').empty();
            $('#paramsDIV').empty();
            $('#module').load(createLink('workflowdatasource', 'ajaxGetAppModules', 'app=' + $(this).val()));
        }
    });

    $(document).on('change', '#module', function()
    {
        if($(this).val())
        {
            $('#method').empty();
            $('.methodDesc').hide();
            $('#paramsDIV').empty();
            $.get(createLink('workflowdatasource', 'ajaxGetModuleMethods', 'app=' + $('#app').val() + '&module=' + $(this).val()), function(methods)
            {
                methods = JSON.parse(methods);
                $.each(methods, function(i, item)
                {
                    $('#method').append("<option value='" + i + "'>" + item + "</option>");
                });
            });
        }
    });

    $(document).on('change', '#method', function()
    {
        if($('#module').val() && $(this).val())
        {
            var method = $(this).val();
            $.get(createLink('workflowdatasource', 'ajaxGetMethodComment', 'app=' + $('#app').val() + '&module=' + $('#module').val() + '&method=' + $(this).val()), function(data)
            {
                $('.methodDesc').show();
                $('#methodDesc').val(data).attr('title', data);
            });

            var link = createLink('workflowdatasource', 'ajaxGetMethodParams', 'app=' + $('#app').val() + '&module=' + $('#module').val() + '&method=' + $(this).val());
            $('#paramsDIV').load(link);
        }
    });

    $(document).on('change', '#sql', function()
    {
        $('#sqlLabel').remove();
        $(this).css({'margin-bottom': 0, 'border-color': ''});

        $.post(createLink('workflowdatasource', 'ajaxCheckSql'), {sql: $(this).val()}, function(response)
        {
            response = JSON.parse(response);
            if(response.result == 'success')
            {
                $('#keyField, #valueField').empty().append(response.options).trigger('chosen:updated');
            }
            if(response.result == 'fail')
            {
                $('#keyField, #valueField').empty().trigger('chosen:updated');

                var errorMSG = '<span id="sqlLabel" for="sql" class="text-error red">' + response.message + '</span>';
                $('#sql').css({'margin-bottom': 0, 'border-color': '#953B39'}).after(errorMSG);
            }
        });
    });


    /* Add a option. */
    $(document).on('click', '.addItem', function()
    {   
        $(this).parents('.input-group').after($('#optionGroup').html());
    }); 

    /* Delete a option. */
    $(document).on('click', '.delItem', function()
    {   
        if($(this).parents('td').find('div.input-group').size() == 1)
        {   
            $(this).parents('.input-group').find('input').val('');
        }   
        else
        {   
            $(this).parents('.input-group').remove();
        }   
    }); 
})
