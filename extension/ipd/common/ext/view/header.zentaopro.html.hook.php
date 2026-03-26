<?php if(strpos($config->version, 'pro') === 0):?>
<script>
$(function()
{
    $('#globalBarLogo a').eq(1).attr('title', '<?php echo str_replace('pro', $lang->proName . ' ', $config->version);?>');
    $('#globalBarLogo a .version').html('<?php echo ' ' . str_replace('pro', $lang->proName . ' ', $config->version);?>');
    $('#globalBarLogo a .upgrade').html('<?php echo $lang->bizName;?>');
})
</script>
<?php endif;?>
