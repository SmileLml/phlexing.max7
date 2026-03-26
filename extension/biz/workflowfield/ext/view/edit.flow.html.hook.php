<?php if($field->field == 'subStatus'):?>
<script>
$(function()
{
    $('#editFieldForm #submit').click(function()
    {
        $(':radio[id^="optionDefault"]').each(function()
        {
            $(this).val($(this).closest('.input-group').find('input[id^="optionCode"]').val());
        })
    })
})
</script>
<?php endif;?>
<?php if($field->default):?>
<script>
$(function()
{
    /* Reset default value when after setDefaultControl. */
    $('#editFieldForm #default').val('<?php echo $field->default;?>');
})
</script>
<?php endif;?>
<script>
$formActions = $('#fieldTable .form-actions');
$formActions.addClass('text-center');
$formActions.closest('tr').find('th').remove();
$formActions.attr('colspan', '4');
</script>
