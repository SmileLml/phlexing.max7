<?php if($app->tab == 'workflow'):?>
<script>
if(typeof(module) == 'string' && module == 'execution')
{
    $('#editorNavLeft strong').html('<?php echo $lang->execution->common;?>');
}
</script>
<?php endif;?>
