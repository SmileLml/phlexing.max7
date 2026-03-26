$(function()
{
    $(document).on('click', '.addItem', function()
    {
        var $tr = $(this).parents('tr');

        $tr.after(window.itemRow.replace(/KEY/g, window.row));
        initSelect($tr.next().find('.picker-select'));
        $tr.next().find('.form-date, .form-datetime').datetimepicker(
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

        window.row++;
    });

    $(document).on('click', '.delItem', function()
    {
        var $tbody = $(this).parents('tbody');
        var $tr    = $(this).parents('tr');

        if($('.delItem').length == 1)
        {
            $tr.find('input[type=text]:visible,select').val('');
            $tr.find('.chosen').trigger('chosen:updated');

            $tr.find('.text-error.red').each(function()
            {
                $(this).prev().css('border-color', '');
                $(this).remove();
            });

            return false;
        }

        $tr.remove();

        $tbody.find('tr:first select').each(function()
        {
            var picker = $(this).data('zui.picker');
            if(picker)
            {
                picker.removeFromList('ditto');
            }
            else
            {
                if($(this).find('option[value=ditto]').length == 1)
                {
                    $(this).find('option[value=ditto]').remove();

                    var chosen = $(this).data('chosen');
                    if(chosen) $(this).trigger('chosen:updated');
                }
            }
        });
    });

    $(document).on('change', 'input,select,textarea,radio,checkbox', function()
    {
        $(this).css('border-color', '');
        $(this).next('.text-error.red').remove();
    });
})
