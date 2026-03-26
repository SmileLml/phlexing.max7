<script>
$(function()
{
    $('#hookDIV .table-form tr:first td.w-100px').width('90').removeClass('w-100px');
    $(document).on('click', '.btn.addWhere', function()
    {
        var $nextWhere = $(this).closest('tr').next();
        $nextWhere.find('#field').chosen();
    })
})
</script>
