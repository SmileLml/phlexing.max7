<?php namespace zin;?>
<?php if(!isonlybody()):?>
<?php
global $app;
$effortHtml = $app->control->loadModel('effort')->createAppendLink('build', data('build.id'));
?>
<script>
$(function()
{
    $('#mainContent .detail-header .btn-group').first().prepend(<?php echo json_encode($effortHtml);?>);
})
</script>
<?php endif;?>
