<?php namespace zin;?>
<?php if(!isonlybody()):?>
<?php
global $app;
$effortHtml = $app->control->loadModel('effort')->createAppendLink('testtask', data('task.id'));
?>
<script>
$(function()
{
    $('#mainContent .detail-body .toolbar .divider').eq(0).after(<?php echo json_encode($effortHtml);?>);
});
</script>
<?php endif;?>
