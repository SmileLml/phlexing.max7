<?php
$designName = $task->design ? zget($designs, $task->design) : '';
$designLink = $designName ? html::a($this->createLink('design', 'view', "id=$task->design"), $designName) : '';
$html  = '<tr>';
$html .= '<th>';
$html .= $lang->task->design;
$html .= '</th>';
$html .= '<td>';
$html .= $designLink;
$html .= '</td>';
$html .= '</tr>';

$onItem     = $version ? $version : $task->version;
$versionUl  = '<small class="dropdown">';
$versionUl .= html::a('#', '#' . $onItem . '<span class="caret"></span>', '', 'data-toggle="dropdown" class="text-muted"');
$versionUl .= '<ul class="dropdown-menu">';
for ($i = $task->version; $i > 0; $i--)
{
    ($onItem == $i) ? $active = 'class="active"' : $active = '';
    $linkVersion = html::a($this->createLink('task', 'view', "taskID=$task->id&version=$i"), "#$i");
    $versionUl  .= '<li ' . $active . '>' . $linkVersion . '</li>';
}
$versionUl .= '</ul></small>';

$delayDesc = '';
if($version)
{
    $today = helper::today();
    $delay = helper::diffDate($today, $taskSpec->deadline);
    if($delay > 0) $delayDesc = sprintf($lang->task->delayWarning, $delay);
}
js::set('delayDesc', $delayDesc);
js::set('versionUl', $versionUl);
js::set('version', $version);
js::set('taskSpec', $taskSpec);
js::set('executionType', $execution->type);
js::set('attribute', $execution->attribute);
?>
<script>
$(function()
{
    if(executionType == 'stage') $('.col-4 #legendBasic table tr').eq(2).after(<?php echo json_encode($html);?>);
    $(".page-title").append(versionUl);
    if(version)
    {
        $(".page-title").children().eq(1).html(taskSpec.name).attr('title',taskSpec.name);
        $("[class='side-col col-4']").children().eq(1).find('tbody').children().eq(3).children().eq(1).html(taskSpec.estStarted);
        $("[class='side-col col-4']").children().eq(1).find('tbody').children().eq(5).children().eq(1).html(taskSpec.deadline + delayDesc);
    }

    if(attribute == 'request' || attribute == 'review') $('.nofixed').hide();
})
</script>
