<script>
$(function()
{
    $('#relationTemplateNext').find('[value="product"]').remove();
    $('#relationTemplateNext').find('[value="productplan"]').remove();
    $('#relationTemplateNext').find('[value="release"]').remove();
    $('#relationTemplateNext').find('[value="project"]').remove();
    $('#relationTemplateNext').find('[value="build"]').remove();
    $('#relationTemplateNext').find('[value="testtask"]').remove();
    $('#relationTemplateNext').find('[value="testsuite"]').remove();
    $('#relationTemplateNext').find('[value="caselib"]').remove();

    $('select[id^=next]').each(function()
    {
        $(this).find('[value="product"]').remove();
        $(this).find('[value="productplan"]').remove();
        $(this).find('[value="release"]').remove();
        $(this).find('[value="project"]').remove();
        $(this).find('[value="build"]').remove();
        $(this).find('[value="testtask"]').remove();
        $(this).find('[value="testsuite"]').remove();
        $(this).find('[value="caselib"]').remove();

        $(this).trigger("chosen:updated");

        if('story,task,bug,testcase,feedback'.indexOf($(this).val()) >= 0)
        {
            $(this).closest('tr').find('[value=one2many]').attr('disabled', 'disabled').closest('.checkbox-primary').hide();
            $(this).closest('tr').find('[value=many2one]').attr('disabled', 'disabled').closest('.checkbox-primary').hide();
            $(this).closest('tr').find('[value=many2many]').attr('disabled', 'disabled').closest('.checkbox-primary').hide();
        }
    })

    $(document).on('change', 'select[id^=next]', function()
    {
        if('story,task,bug,testcase,feedback'.indexOf($(this).val()) >= 0)
        {
            $(this).closest('tr').find('[value=one2many]').attr('disabled', 'disabled').closest('.checkbox-primary').hide();
            $(this).closest('tr').find('[value=many2one]').attr('disabled', 'disabled').closest('.checkbox-primary').hide();
            $(this).closest('tr').find('[value=many2many]').attr('disabled', 'disabled').closest('.checkbox-primary').hide();
        }
        else
        {
            $(this).closest('tr').find('[value=one2many]').removeAttr('disabled').closest('.checkbox-primary').show();
            $(this).closest('tr').find('[value=many2one]').removeAttr('disabled').closest('.checkbox-primary').show();
            $(this).closest('tr').find('[value=many2many]').removeAttr('disabled').closest('.checkbox-primary').show();
        }
    });
})
</script>
