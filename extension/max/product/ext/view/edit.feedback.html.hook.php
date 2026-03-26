<?php
$html  = '<tr>';
$html .= '<th>' . $lang->product->FM . '</th>';
$html .= '<td>' . html::select('feedback', $rdUsers, $product->feedback, 'class="form-control chosen"') . '</td>';
$html .= '</tr>';
?>
<script>
    $('#RD').parent().parent().after(<?php echo json_encode($html)?>);
</script>
