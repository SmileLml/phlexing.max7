<script>
$(function()
{
    $('#trip').closest('tr').hide();
    $('#begin, #end, #hours, #overtime').parent().addClass('required');
    /* Button in the middle.  */
    $('#ajaxForm tr:last th').remove();
    $('#ajaxForm tr:last td:last').remove();
    $('#ajaxForm tr:last td').attr('colspan',3);
    $('#ajaxForm tr:last').addClass('text-center');
});
</script>
