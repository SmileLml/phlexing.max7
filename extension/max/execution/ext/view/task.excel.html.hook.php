<?php
$class = common::hasPriv('task', 'exportTemplate') ? '' : "class='disabled'";
$link  = common::hasPriv('task', 'exportTemplate') ? $this->createLink('task', 'exportTemplate', "executionID=$executionID") : '#';
$misc  = common::hasPriv('task', 'exportTemplate') ? "class='exportTemplate'" : "class='disabled'";
$exportHtml = "<li $class>" . html::a($link, $lang->task->exportTemplate, '', $misc) . '</li>';

$class = common::hasPriv('task', 'import') ? '' : "class='disabled'";
$link  = common::hasPriv('task', 'import') ? $this->createLink('task', 'import', "executionID=$executionID") : '#';
$misc  = common::hasPriv('task', 'import') ? "class='importExcel'" : "class='disabled'";
$importHtml = "<li $class>" . html::a($link, $lang->task->import, '', $misc) . '</li>';
?>
<script>
$(function()
{
    $('#exportActionMenu').append(<?php echo json_encode($exportHtml);?>);
    $('#importActionMenu').prepend(<?php echo json_encode($importHtml);?>);
    $(".importExcel").modalTrigger({width:650, type:'iframe'});
    $(".exportTemplate").modalTrigger({width:650, type:'iframe'});
})
</script>
