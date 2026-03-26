$(document).ready(function()
{
    $.ajaxForm('#addVarForm', function(data)
    {
        if(data.result == 'success')
        {
            var varName = data.varName;
            $('#varsTR').show();
            $('#varsTD').append($('#varGroup').html().replace(/key/g, varName));
            var link = createLink('workflow', 'buildVarControl', 'varName=' + varName);
            $('#varsTD').find('#' + varName).find('.input-group').load(link, function()
            {
                $('#varsTD').find('#' + varName).find('.chosen').chosen();
                $('#varsTD').find('#' + varName).find('.form-date').fixedDate().datetimepicker($.extend(window.datetimepickerDefaultOptions, {startView: 2, minView: 2, format: 'yyyy-mm-dd'}));
                fixVarControls();
            });
            $('#sql').val($('#sql').val() + "'$" + varName + "'");

            $.zui.closeModal();
        }
    });

    $('#fieldTable #control').change();

    $('#optionTR > td').sortable(
    {
        selector: 'div.input-group',
        trigger: '.sortItem',
        dragCssClass: 'drag-row',
        reverse: true,
    });
});
