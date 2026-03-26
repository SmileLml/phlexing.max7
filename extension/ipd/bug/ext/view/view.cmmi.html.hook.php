<?php
if($bug->identify)
{
    $review   = $this->loadModel('review')->getByID($bug->identify);
    $identify = $review->title . '-' . $review->version;
}
else
{
    $identify = '';
}

$html  = "<tr>";
$html .= "<th>" . $lang->bug->identify . "</th>";
$html .= "<td>" . $identify;
$html .= '</td>';
$html .= '</tr>';
?>
<script>
$('#legendBasicInfo .table-data tr:eq(-3)').after(<?php echo json_encode($html);?>);
</script>
