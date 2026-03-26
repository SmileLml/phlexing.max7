<?php namespace zin;?>
<?php if($type == 'tree' or $type == 'calendar' or $type == 'kanban'):?>
<?php
global $app;
$app->session->set('taskOnlyCondition', false, 'execution');
$app->session->set('taskQueryCondition', false, 'execution');
?>
<script>
$(function()
{
    $('#exportPanel .form-group[data-name=fileType]').remove();
    $('#exportPanel [name=fileType]').remove();
    $('#exportPanel .form-actions').after("<input type='hidden' name='fileType' id='fileType' value='<?php echo $type;?>' />");
    $('#exportPanel .form-actions').after("<input type='hidden' name='executionID' id='executionID' value='<?php echo $executionID;?>' />");
    $('#exportPanel .form-group, #exportPanel .form-row').addClass('hidden');
    $('#exportPanel .form-group[data-name=fileName], #exportPanel .form-row:last-child').removeClass('hidden');
    <?php if($type == 'kanban'):?>
    <?php list($kanbanType, $orderBy) = explode(',', $orderBy);?>
    $('#exportPanel .form-actions').after("<input type='hidden' name='type' id='type' value='<?php echo $kanbanType;?>' />");
    $('#exportPanel .form-actions').after("<input type='hidden' name='orderBy' id='orderBy' value='<?php echo $orderBy;?>' />");
    <?php endif;?>
})
</script>
<?php endif;?>
