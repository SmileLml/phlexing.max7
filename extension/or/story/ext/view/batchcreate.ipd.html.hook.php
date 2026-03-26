<?php
$htmlTh  = "<th class='c-duration " . zget($visibleFields, 'duration', ' hidden') . zget($requiredFields, 'duration', '', ' required') . " durationBox'>{$lang->story->duration}</th>";
$htmlTh .= "<th class='c-BSA " . zget($visibleFields, 'BSA', ' hidden') . zget($requiredFields, 'BSA', '', ' required') . " BSABox'>{$lang->story->BSA} ";
$htmlTh .= "<icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content='{$lang->demand->bsaTip}'></icon>";
$htmlTh .= "</th>";

$htmlTd  = "<td class='text-left" . zget($visibleFields, 'duration', ' hidden') . " durationBox' style='overflow:visible'>" . html::select('duration[$id]', $lang->demand->durationList, '', "class='form-control chosen'") . "</td>";
$htmlTd .= "<td class='text-left" . zget($visibleFields, 'BSA', ' hidden') . " BSABox' style='overflow:visible'>" . html::select('BSA[$id]', $lang->demand->bsaList, '', "class='form-control chosen'") . "</td>";

$templateTd  = "<td class='text-left" . zget($visibleFields, 'duration', ' hidden') . " durationBox' style='overflow:visible'>" . html::select('duration[%i%]', $lang->demand->durationList, '', "class='form-control chosen'") . "</td>";
$templateTd .= "<td class='text-left" . zget($visibleFields, 'BSA', ' hidden') . " BSABox' style='overflow:visible'>" . html::select('BSA[%i%]', $lang->demand->bsaList, '', "class='form-control chosen'") . "</td>";

$this->session->set('storyList', $this->createLink('product', 'browse', "productID=$product->id&branch=$branch&browseType=allstory"), 'product');
?>
<script>
$('.c-category').after(<?php echo json_encode($htmlTh);?>);
$('.categoryBox').after(<?php echo json_encode($htmlTd);?>);
$('#addRow select[name*="category"]').after(<?php echo json_encode($templateTd);?>);
/* Spec always show. */
if($('.specBox').hasClass('hidden')) $('.specBox').removeClass('hidden')
$('[data-toggle="popover"]').popover();
</script>
