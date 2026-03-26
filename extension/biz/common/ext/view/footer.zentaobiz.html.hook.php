<?php if(strpos($config->version, 'biz') === 0):?>
<script>
$(function()
{
    $('#poweredBy a').eq(1).html('<i class="icon-zentao"></i> <?php echo '<span class="version">' . $lang->zentaoPMS . str_replace('biz', $lang->bizName . ' ', $config->version) . '</span>';?>');
})
</script>
<?php endif;?>
<?php if(isset($this->session->bizIoncubeProperties->expireDate) and $this->session->bizIoncubeProperties->expireDate):?>
<?php $expireDate = $this->session->bizIoncubeProperties->expireDate;?>
<?php $limitUsers  = isset($this->session->bizIoncubeProperties->user) ? $this->session->bizIoncubeProperties->user : 0;?>
<script>
<?php
$expireDate = strtolower($expireDate) == 'all life' ? $lang->forever : sprintf($lang->expireDate, $expireDate);
$limitUsers = $limitUsers == 0 ? $lang->unlimited : sprintf($lang->licensedUser, $limitUsers);
?>
$('#poweredBy a').eq(1).attr('title', '<?php echo $expireDate . "，" . $limitUsers; ?>');
</script>
<?php endif;?>
<?php if(isset($this->session->bizIoncubeProperties->userLimited) and $this->session->bizIoncubeProperties->userLimited):?>
<script>
$(function()
{
    $('#poweredBy').after("<?php echo $lang->noticeBizLimited?>");
    $('#bizUserLimited').css('margin-right', '50px');
    if($('#userLimited').size() > 0) $('#userLimited').css('margin-left', 5);
})
</script>
<?php endif;?>
