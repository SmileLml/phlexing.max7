<?php if(!empty($doc) and !$doc->deleted and $doc->version > 1 and common::hasPriv('doc', 'diff')):?>
<?php
$versions = array();
$i = 1;
foreach($actions as $action)
{
    if($action->action == 'created' or $action->action == 'deletedfile' or $action->action == 'commented')
    {
        $versions[$i] =  "#$i " . zget($users, $action->actor) . ' ' . substr($action->date, 2, 14);
        $i++;
    }
    elseif($action->action == 'edited')
    {
        foreach($action->history as $history)
        {
            if($history->field == 'content')
            {
                $versions[$i] = "#$i " . zget($users, $action->actor) . ' ' . substr($action->date, 2, 14);
                $i++;
                break;
            }
        }
    }
}
krsort($versions);

$diffHtml  = "<div class='btn-group versions'>";
$diffHtml .= "<button data-toggle='dropdown' class='btn btn-link'>{$lang->doc->diff} <span class='caret'></span></button>";
$diffHtml .= "<ul class='dropdown-menu pull-right'>";
foreach($versions as $i => $versionTitle)
{
    $diffHtml .= "<li class='v{$i}'>" . html::a(inlink('diff', "objectType={$objectType}&docID={$doc->id}&newVersion=%currentVersion%&version={$i}"), $versionTitle) . '</li>';
}
$diffHtml .= "</ul>";
$diffHtml .= "</div>";
?>
<script>
appendDiffLink();

/* Append diff link when change doc version. */
$(document).on('click', '.doc-version-menu a, #mainActions .container a', function(event)
{
    var times = 0;
    var timer = setInterval(function()
    {
        times ++;

        if($('#mainContent #content .detail-title .actions .versions').length == 0)
        {
            appendDiffLink();
            clearInterval(timer);
        }

        if(times >= 300) clearInterval(timer);
    }, 100);
});

/**
 * Append diff link.
 *
 * @access public
 * @return void
 */
function appendDiffLink()
{
    var currentVersion = $('#mainContent #content .info .version').attr('data-version');
    var diffHtml = <?php echo json_encode($diffHtml)?>;
    var diffHtml = diffHtml.replace(/%currentVersion%/g, currentVersion);
    $('#mainContent #content .detail-title .actions').append(diffHtml);
    $('#mainContent #content .detail-title .actions .versions').find('.v' + currentVersion).remove();
}
</script>
<?php endif;?>
