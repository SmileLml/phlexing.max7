<?php
$html  = '<tr>';
$html .= '<th>' . $lang->product->TM . '</th>';
$html .= '<td>' . html::select('ticket', $rdUsers, $product->ticket, 'class="form-control chosen"') . '</td>';
$html .= '</tr>';
?>
<script>
    $('#feedback').parent().parent().after(<?php echo json_encode($html)?>);
</script>
