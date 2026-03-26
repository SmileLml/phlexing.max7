<?php namespace zin;?>
<?php if(!isonlybody()):?>
<?php
global $app;
$effortHtml = $app->control->loadModel('effort')->createAppendLink('productplan', data('plan.id'));
?>
<script>
$(function()
{
    $('#mainContent .detail-header .btn-group').first().prepend(<?php echo json_encode($effortHtml);?>);
    $('#mainContent .detail-header .btn-group i').eq(0).addClass('text-primary');
})
</script>
<?php endif;?>
