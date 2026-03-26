$(function()
{
    $('.form-date').datetimepicker('setEndDate', today);
    $('#left').parents('tr').toggle(objectType == 'task');

    $("#submit").click(function(e, confirmed)
    {
        if(confirmed) return true;

        var $this = $(this);
        var hasZero = false;
        var $left   = $("input[name^='left']");
        var left    = $.trim($left.val());
        var objectType = $left.parents('table').find('#objectType').val();
        if(objectType == 'task' && $left.attr('disabled') != 'disabled' && !$left.prop('readonly') && left == 0) hasZero = true;
        if(hasZero)
        {
            e.preventDefault();
            bootbox.confirm(noticeFinish, function(result)
            {
                if(!result) $this.attr("disabled", false);
                if(result) $this.trigger('click', true);
            });
        }
    });
})
