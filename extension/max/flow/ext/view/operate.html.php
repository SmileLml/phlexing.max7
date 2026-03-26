<?php
if($action->open == 'modal')
{
    $title = "<span class='label label-id'>{$dataID}</span> <span title='$title'>{$action->name}</span>";
}
js::set('openInModal', $action->open == 'modal');

$oldDir = getcwd();
chdir(dirname(dirname($oldDir)) . '/view');
include './operate.html.php';
chdir($oldDir);
?>
<?php if($action->open == 'normal'):?>
<script>
$(function()
{
    var moduleNavigator = '<?php echo $flow->navigator;?>';
    var moduleApp       = '<?php echo $flow->app;?>';

    $('#navbar li[data-id=<?php echo $flow->module;?>]').addClass('active');
})
</script>
<?php endif;?>
<script>
$(function()
{
    $('#contactListMenu').attr("onchange", "setMailto('mailto', this.value)");
})

function setMailto(mailto, contactListID)
{
    $('#contactListMenu').prev().remove();
    link = createLink('user', 'ajaxGetContactUsers', 'listID=' + contactListID);
    $.get(link, function(users)
    {
        $('#' + mailto).replaceWith(users);
        $('#' + mailto + '_chosen').remove();
        $('#' + mailto).chosen();
    });
}
</script>
