<?php namespace zin;?>
<?php if(!isonlybody()):?>
<?php
global $app;
$effortHtml = $app->control->loadModel('effort')->createAppendLink('release', data('release.id'));
?>
<script>
$(function()
{
    $('#mainContent .detail-header .btn-group').first().prepend(<?php echo json_encode($effortHtml);?>);
})
</script>
<?php endif;?>
