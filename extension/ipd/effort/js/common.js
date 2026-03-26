function setLeftInput(obj)
{
    if($(obj).val().indexOf('task_') >= 0)
    {
        $(obj).parent().next().find('input').attr('disabled', false);
        $(obj).parent().next().find('input').removeClass('disabled');
    }
    else
    {
        $(obj).parent().next().find('input').attr('disabled', true);
        $(obj).parent().next().find('input').addClass('disabled');
    }
}
