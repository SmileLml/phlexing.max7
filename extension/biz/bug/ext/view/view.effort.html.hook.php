<?php $effortHtml = $this->loadModel('effort')->createAppendLink('bug', $bug->id); ?>
<script>
$('#mainContent .main-actions:first .btn-toolbar .divider:first').after(<?php echo json_encode($effortHtml);?>);
$('#mainContent .main-actions').css('min-width', '820px'); // Fix bug #23783.
$(function()
{
    $(".effort").modalTrigger({width:1024, iframe:true, transition:'elastic'});
})
</script>
