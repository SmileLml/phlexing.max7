<?php if(strpos($config->version, 'max') === 0 and $config->vision == 'rnd'):?>
<script>
$(function()
{
    $('#globalBarLogo a').eq(1).attr('title', '<?php echo ($config->inQuickon ? $lang->devopsPrefix : '') . str_replace('max', $lang->maxName . ' ', $config->version);?>');
    $('#globalBarLogo a .version').html('<?php echo ' ' . ($config->inQuickon ? $lang->devopsPrefix : '') . str_replace('max', $lang->maxName . ' ', $config->version);?>');
    $('#globalBarLogo a .upgrade').html('<?php echo $lang->upgrade->common;?>');
})
</script>
<?php endif;?>
