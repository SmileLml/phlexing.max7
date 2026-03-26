function updateAction(date)
{
    date = date.replace(/\-/g, '');
    link = createLink('effort', 'batchCreate', 'date=' + date);

    var hasContent = false;
    $('#objectTable tr.effortBox.new input[id^=work]').each(function(){if($(this).val().length > 0) hasContent = true});
    if(!hasContent) return location.href=link;

    cleanEffort();
}

function addEffort(clickedButton)
{
    effortRow = '<tr class="effortBox new">' + $(clickedButton).closest('tr').html() + '</tr>';
    $(clickedButton).closest('tr').after(effortRow);
    var nextBox = $(clickedButton).closest('tr').next('.effortBox');
    $(nextBox).find('input[id^=id]').val(num);
    $(nextBox).find('.chosen-container, .picker').remove();
    $(nextBox).find('select').chosen();
    $(nextBox).find('input[id^="left"]').attr('name', "left[" + num + "]").attr('id', "left[" + num + "]");
    $(nextBox).find('select[id^=execution]').attr('name', "execution[" + num + "]").attr('id', "execution" + num);
    if($(nextBox).find('select#objectType').val().indexOf('task_') < 0) $(nextBox).find('input[id^="left"]').attr('disabled', 'disabled');

    num++;
    updateID();
}

function deleteEffort(clickedButton)
{
    if($('.effortBox').size() == 1) return;
    $(clickedButton).parent().parent().remove();
    updateID();
}

function cleanEffort()
{
    $('#objectTable tbody tr.computed').remove();
    updateID();
}

function updateID()
{
    i = 1;
    $('.effortID').each(function(){$(this).html(i ++)});
}

$(function()
{
    $('select#objectType').each(function()
    {
        var value = $(this).val();
        var $leftInput = $(this).closest('td').next().next().find('input');
        if(value.indexOf('task_') >= 0)
        {
            $leftInput.removeAttr('disabled').removeAttr('title');
        }
    });

    $(document).on('change', 'select#objectType', function()
    {
        var value       = $(this).val();
        var executionID = value.indexOf('task') > -1 ? (executionTask[value] ? executionTask[value] : 0) : (executionBug[value] ? executionBug[value] : 0);

        var id         = $(this).closest('tr').find('#id').val();
        var selectName = 'execution[' + id + ']';
        var selectID   = 'execution' + id;
        if(value == '')
        {
            var executionTpl = $('#executionTpl').html();
        }
        else
        {
            var executionName = executions[executionID] ? executions[executionID] : '';

            var executionTpl = '<select name="' + selectName + '" id="' + selectID + '" tabindex="9999" class="form-control">';
            executionTpl    += '<option value="" title="" data-keys=" "></option>';
            if(executionName) executionTpl += '<option value="' + executionID + '" title="' + executionName + '" data-keys=" ">' + executionName + '</option>';
        }

        var $executionTd = $(this).parent().next();
        $executionTd.empty();
        $executionTd.append(executionTpl);

        var $execution = $(this).parent().next().find('select');
        $execution.chosen();
        $execution.val(executionID);
        $execution.trigger("chosen:updated");

        var $leftInput = $(this).closest('td').next().next().find('input');
        if(value.indexOf('task_') >= 0)
        {
            $leftInput.removeAttr('disabled').removeAttr('title');
        }
        else
        {
            $execution.val(0);
            $leftInput.attr('disabled', true).attr('title', leftTip);
        }
    });
});
