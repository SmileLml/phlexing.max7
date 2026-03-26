<?php if($execution->type == 'stage'):?>
<?php
$html  = '<tr>';
$html .= '<th>';
$html .= $lang->task->design;
$html .= '</th>';
$html .= '<td colspan=3>';
$html .= html::select('design', '', '', "class='form-control chosen'");
$html .= '</td>';
$html .= '<tr>';
?>
<script>
$(function()
{
    $('#story').closest('tr').after(<?php echo json_encode($html);?>);
    $('#design').chosen();
})
</script>
<?php endif;?>
