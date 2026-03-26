<?php
$properties = $this->loadModel('api')->getLicenses();
$html       = '';
if(isset($properties['expireDate']) and $properties['expireDate'] != 'All Life')
{
    $expireDays    = helper::diffDate($properties['expireDate'], date('Y-m-d'));
    $expireWarning = $lang->user->expireWarning;
    if(strpos($this->config->version, 'biz') !== false) $expireWarning = $lang->user->expireBizWaring;
    if(strpos($this->config->version, 'max') !== false) $expireWarning = $lang->user->expireMaxWaring;
    if($expireDays <= 30 and $expireDays > 0) $html = sprintf($expireWarning, $expireDays);
    if($expireDays == 0) $html = $lang->user->expiryReminderToday;
}
if(isset($properties['serviceDeadline']) and !helper::isZeroDate($properties['serviceDeadline']) and isset($properties['expireDate']) and $properties['expireDate'] == 'All Life')
{
    $serviceLeftDays = helper::diffDate($properties['serviceDeadline'], date('Y-m-d'));
    $serviceWarning  = $lang->user->serviceWarning;
    if($serviceLeftDays <= 30 and $serviceLeftDays > 0) $html = sprintf($serviceWarning, $serviceLeftDays);
    if($serviceLeftDays == 0) $html = $lang->user->serviceReminderToday;
    if($serviceLeftDays < 0)  $html = $lang->user->expiredServiceWarning;
}

?>
<script>
$(function()
{
    $('#poweredby').append(<?php echo json_encode($html);?>);
})
</script>
