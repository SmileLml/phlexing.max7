<?php js::set('type', $type)?>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('lang.confirmDelete', $lang->confirmDelete);?>
<?php js::set('lang.deleteing', $lang->deleteing);?>
<script>
$(function()
{
    $('.reviewPass').attr('data-toggle', 'ajax');

    $("input[name^='leaveIDList']").remove();
    $('.checkbox-inline, .radio-inline').removeClass('checkbox-inline');

    var link = createLink('leave', 'export', "mode=all&orderBy=" + orderBy + "&type=" + type);
    $('#menuActions .btn-link').attr('href', link);
})
</script>
