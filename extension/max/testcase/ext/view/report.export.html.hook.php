<?php if(common::hasPriv('report', 'export')):?>
<script>
$(function()
{
    $('#mainMenu').prepend(<?php echo json_encode("<div class='btn-toolbar pull-right'>" . html::a($this->createLink('report', 'export', 'module=' . $this->app->getModuleName()), $lang->export, '', "class='btn btn-primary' id='exportchart' data-group='project'") . '</div>')?>);
    $('#exportchart').modalTrigger();
});
</script>
<?php endif;?>
