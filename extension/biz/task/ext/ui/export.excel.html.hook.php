<?php
namespace zin;
$type        = data('type');
$executionID = data('executionID');
$orderBy     = data('orderBy');
?>

<?php if($type == 'group'):?>
<script>
$(function()
{
    $('#exportPanel .form-group, #exportPanel .form-row').addClass('hidden');
    $('#exportPanel .form-group[data-name=fileName], #exportPanel .form-row:last-child').removeClass('hidden');
    $('#exportPanel .form-group[data-name=fileType]').remove();
    $('#exportPanel .form-actions').after("<input type='hidden' name='fileType' value='<?php echo $type;?>' />");
    $('#exportPanel .form-actions').after("<input type='hidden' name='executionID' value='<?php echo $executionID;?>' />");
    $('#exportPanel .form-actions').after("<input type='hidden' name='orderBy' value='<?php echo $orderBy;?>' />");
})
</script>
<?php endif;?>

<?php if($type == 'tree'):?>
<?php
$radioItems = array('0' => 'word', 'excel' => 'excel');
query('#exportPanel .form-group:first-child')->append(
    radioList
    (
        setClass('ml-4'),
        set::name('excel'),
        set::items($radioItems),
        set::inline(true)
    )
);
?>
<script>
$(function()
{
    $('#exportPanel .form-group[data-name=fileType]').remove();
    $('#exportPanel .form-actions').after("<input type='hidden' name='fileType' value='<?php echo $type;?>' />");
    $('#exportPanel .form-group, #exportPanel .form-row').addClass('hidden');
    $('#exportPanel .form-group[data-name=fileName], #exportPanel .form-row:last-child').removeClass('hidden');
    $('#exportPanel .form-group[data-name=fileName] [name=fileName]').addClass('w-2/3');
    $('#exportPanel .form-group[data-name=fileName] #excel_0').trigger('click');
})
</script>
<?php endif;?>
