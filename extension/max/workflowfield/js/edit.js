$(document).ready(function()
{
    if($('#expression').val())
    {
        window.expression = JSON.parse($('#expression').val());

        $('#fieldTable .expression').append("<span class='item-name'>" + $('#name').val() + '</span><span>=</span>');

        appendExpression(window.expression, $('#fieldTable .expression'));
    }

    $.ajaxForm('#editFieldForm', function(response)
    {
        if(response.alert)
        {
            $('#editFieldForm #submit').hide();
            $('#editFieldForm #alert').html(response.alert);
            $('#editFieldForm #alertDIV').show();

            $('#editFieldForm #alertBtn').click(function()
            {
                location.href = location.href;
            });
        }
        else
        {
            if(response.result == 'success')
            {
                var reloadUrl = response.locate == 'reload' ? location.href : response.locate;
                setTimeout(function(){location.href = reloadUrl;}, 1200);
            }
        }
    });

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
            $('#sql').val($('#sql').val() + ' $' + varName + ' ');

            $.zui.closeModal();
        }
    });

    $('#fieldTable #control').change();

    fixVarControls();
    showTips();

    $('#optionTR td.sortTd').sortable(
    {
        selector: 'div.input-group',
        trigger: '.sortItem',
        dragCssClass: 'drag-row',
        reverse: true
    });

    /* Add a option. */
    $(document).on('click', '.addItem', function()
    {
        $(this).closest('.sortTd').find('.sort-btn').removeClass('hidden');
    });
});

function showTips()
{
    let $tipsBox = $('#tipsBox');
    if($tipsBox.length == 0) return;

    let optionType = $('[name=optionType]').val();
    let control    = $('[name=control]').val();
    let tips       = syncQuoteNoOptions.replace('%s', joinedGroupName);
    if(['select', 'multi-select', 'radio', 'checkbox'].includes(control) && optionType == 'custom')
    {
        tips = syncQuoteFields.replace('%s', joinedGroupName);
    }
    $tipsBox.html(tips);
    if(control == 'file') $tipsBox.find('.nosyncbox').addClass('hidden');
}
