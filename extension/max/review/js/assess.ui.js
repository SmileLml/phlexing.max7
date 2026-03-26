window.toggleOption = function(obj)
{
    let $this    = $(obj);
    let result   = $this.val();
    let $opinion = $this.closest('tr').find('.opinion');
    $opinion.attr('readonly', 'readonly');
    if(result == 0) $opinion.removeAttr('readonly');
    $opinion.trigger('focus');
}
