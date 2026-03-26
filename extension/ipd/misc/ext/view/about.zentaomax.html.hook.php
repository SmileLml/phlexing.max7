<?php
if($config->edition == 'max')
{
    $version = $config->version;
    $version = str_replace('max', $lang->maxName . ' ', $config->version);
}
?>
<script>
$(function()
{
    $('#mainContent h4').html(<?php echo json_encode($version);?>);
})
</script>
