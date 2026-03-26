<?php
if($config->edition == 'ipd')
{
    $version = $config->version;
    $version = str_replace('ipd', $lang->ipdName . ' ', $config->version);
}
?>
<script>
$(function()
{
    $('#mainContent h4').html(<?php echo json_encode($version);?>);
})
</script>
