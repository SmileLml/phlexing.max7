<?php if($from == 'repo'):?>
<script>
$(function()
{
    $('.main-actions [href*=story]').hide();
    $('.main-actions [href*=testcase]').hide();
})
</script>
<?php endif;?>
