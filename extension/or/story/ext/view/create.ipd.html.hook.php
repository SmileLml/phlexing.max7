<?php $this->app->loadLang('demand');?>
<?php
$html  = "<tr class='ipdBox'>";
$html .= "<th>{$lang->story->duration}</th>";
$html .= "<td colspan='2'>" . html::select('duration', $this->lang->demand->durationList, '', "class='form-control chosen'") . "</td>";
$html .= "<td colspan='2'>";
$html .= "<div class='input-group'>";
$html .= "<span class='input-group-addon'>{$lang->story->BSA} ";
$html .= "<icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content='{$lang->demand->bsaTip}'></icon>";
$html .= "</span>";
$html .= html::select('BSA', $lang->demand->bsaList, '', "class='form-control picker-select'");
$html .= "</div>";
$html .= "</td>";
$html .= "</tr>";

$this->session->set('storyList', $this->createLink('product', 'browse', "productID=$product->id&branch=$branch&browseType=allstory"), 'product');

$keywords = isset($feedback) ? $feedback->keywords : '';
js::set('keywords', $keywords);
?>
<style>
#dataform .icon-help {position: relative; top: -1px;}
</style>
<script>
if(keywords) $('#keywords').val(keywords);
$('#reviewerBox').parent('tr').after(<?php echo json_encode($html);?>);
$('.table .form-actions').append('<input type="hidden" name="vision" value="or">');
$('[data-toggle="popover"]').popover();
</script>
