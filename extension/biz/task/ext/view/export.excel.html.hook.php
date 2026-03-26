<?php if($type == 'group'):?>
<script>
$(function()
{
    $('.main-form .table tr').addClass('hidden');
    $('.main-form .table tr:first').removeClass('hidden');
    $('.main-form .table tr:last').removeClass('hidden');
    $('.main-form .table #submit').closest('tr').removeClass('hidden');
    $('.main-form .table #fileType').remove();
    $('.main-form .table #submit').after("<input type='hidden' name='fileType' id='fileType' value='<?php echo $type;?>' />");
    $('.main-form .table #submit').after("<input type='hidden' name='executionID' id='executionID' value='<?php echo $executionID;?>' />");
    $('.main-form .table #submit').after("<input type='hidden' name='orderBy' id='orderBy' value='<?php echo $orderBy;?>' />");
})
</script>
<?php endif;?>
<?php if($type == 'tree'):?>
    <script>
        $('.main-form .table').find('tr').eq(0).find('td').eq(1).remove();
        $('.main-form .table tr:first').append("<td><input type='radio' name='excel' value='0' checked style='vertical-align:text-bottom; margin-bottom:1px;'/> word  <input type='radio' name='excel' value='excel' style='vertical-align:text-bottom; margin-bottom:1px;'/> excel</td>");
    </script>
<?php endif;?>
