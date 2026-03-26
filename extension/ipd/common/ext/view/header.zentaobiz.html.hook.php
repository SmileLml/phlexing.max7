<style>
<?php if(defined('IN_USE') and common::checkNotCN()):?>
#navbar ul.nav li a{padding:10px 5px;}
<?php if($this->app->tab != 'admin'):?>
#navbar ul.nav{padding-left:60px;}
#<?php endif;?>
#navbar ul.nav li.divider{margin:15px 5px;}
#navbar .nav .dropdown-menu li > a {padding: 2px 10px;}
<?php else:?>
#navbar ul.nav{padding-left:10px;}
#navbar ul.nav li.divider{margin:15px 10px;}
<?php endif;?>
</style>
<?php if(strpos($config->version, 'biz') === 0 and $config->vision == 'rnd'):?>
<script>
$(function()
{
    $('#globalBarLogo a').eq(1).attr('title', '<?php echo str_replace('biz', $lang->bizName . ' ', $config->version);?>');
    $('#globalBarLogo a .version').html('<?php echo ' ' . str_replace('biz', $lang->bizName . ' ', $config->version);?>');
    $('#globalBarLogo a .upgrade').html('<?php echo $lang->maxName;?>');
})
</script>
<?php endif;?>
