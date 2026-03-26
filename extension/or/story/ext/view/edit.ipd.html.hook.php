<?php
$this->app->loadLang('demand');
$html  = "<tr>";
$html .= "<th>{$lang->story->duration}</th>";
$html .= "<td>" . html::select('duration', $this->lang->demand->durationList, $story->duration, "class='form-control chosen'") . "</td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<th>{$lang->story->BSA} ";
$html .= "<icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content='{$lang->demand->bsaTip}'></icon>";
$html .= "</th>";
$html .= "<td>" . html::select('BSA', $this->lang->demand->bsaList, $story->BSA, "class='form-control chosen'") . "</td>";
$html .= "</tr>";
?>
<script>
$('#category').closest('tr').after(<?php echo json_encode($html);?>);
$('[data-toggle="popover"]').popover();
</script>
