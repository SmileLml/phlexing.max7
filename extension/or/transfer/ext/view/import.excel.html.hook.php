<?php if($this->app->rawModule == 'demand' and (common::hasPriv('demand', 'exportTemplate') or $this->app->user->admin)):?>
<?php
$exportDomHtml = html::a('javascript:void(0);', $this->lang->demand->exportTemplate, '', 'class="btn btn-link btn-active-text text-primary" onclick="exportTemplate()"');
$templateLink  = $this->createLink('demand', 'exportTemplate');
js::set('exportDomHtml', $exportDomHtml);
js::set('templateLink', $templateLink);
?>

<form method='post' action="<?php echo $templateLink;?>" class='hidden' id='exportTemplateForm'>
  <?php echo html::input('num', 10, "class='form-control'");?>
  <?php echo html::input('fileType', 'xlsx', "class='form-control'");?>
  <?php echo html::submitButton();?>
</form>

<style>
#mainContent .text-left a {margin-left: 20px;}
</style>

<script>
$(function()
{
    $('#mainContent .text-left').append($(exportDomHtml));
});
function exportTemplate()
{
    $('#exportTemplateForm #submit').click();
}
</script>
<?php endif;?>
