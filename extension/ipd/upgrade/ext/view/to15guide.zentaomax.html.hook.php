<script>
$(function()
{
    $('[name="mode"]').removeAttr('checked');
    $('#modenew').attr('checked', 'checked').change();
    $('#modeclassic').closest('.radio-inline').remove();
    $('#upgradeTips').removeClass('hidden');
    $('#modenew').closest('.radio-inline').hide();
    $('#modenew').closest('.radio-inline').prev().hide();
    $('#selectedModeTips').hide();
})
</script>
