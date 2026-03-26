<?php
$class       = $currentApp ? '' : "class='active'";
$method      = 'browseFlow';
$featurabar  = "<div id='featurebar'><ul class='nav'>";
$featurabar .= "<li $class>" . baseHTML::a(inlink($method, "mode=$mode&status=&app=&orderBy=$orderBy"), $lang->workflow->all) . '</li>';

$flowApps = $this->workflow->getFlowApps();
foreach($flowApps as $appCode)
{
    $appName = zget($apps, $appCode, '');
    if($appCode == 'project') $appName = $lang->project->common;
    if(!$appName) continue;

    $class = $appCode == $currentApp ? "class='active'" : '';

    $featurabar .= "<li $class>" . baseHTML::a(inlink($method, "mode=$mode&status=&app=$appCode&orderBy=$orderBy"), $appName) . '</li>';
}
$featurabar .= "</ul></div>";
?>
<script>
$(function()
{
    if($('#featurebar').length == 0) $('#main .container').prepend(<?php echo json_encode($featurabar);?>);
    $('a[disabled=disabled]').addClass('disabled');
    $('.deleter').attr('data-toggle', 'ajax');
    $('#bysearchTab').remove();
    $('.btn-browse').each(function()
    {
        var flowApp = $(this).parents('.flow-block').data('flowapp');
        if(flowApp == 'scrum' || flowApp == 'waterfall') flowApp = 'project';
        $(this).attr('href', $(this).attr('href') + '#app=' + flowApp);
    });
});

var $projectTR = $('#workflowTable tr[data-module=project]');
if(!$projectTR.find('td').eq(3).html()) $projectTR.find('td').eq(3).html('<?php echo $lang->project->common;?>');
$projectTR.find('td').eq(1).attr('title', '<?php echo $lang->project->common;?>');
$projectTR.find('td').eq(1).find('a').html('<?php echo $lang->project->common;?>');

var $executionTR = $('#workflowTable tr[data-module=execution]');
$executionTR.find('td').eq(1).attr('title', '<?php echo $lang->execution->common;?>');
$executionTR.find('td').eq(1).find('a').html('<?php echo $lang->execution->common;?>');

$('#cardMode .panel[data-flowapp=project]').find('.panel-body .info div > .text-primary').html('<?php echo $lang->project->common;?>');

var $projectCard = $('div.footerbar[data-module=project]').closest('#flowBlock');
$projectCard.find('.panel-heading strong.title').attr('title', '<?php echo $lang->project->common;?>');
$projectCard.find('.panel-heading strong.title').html('<?php echo $lang->project->common;?> ' + $projectCard.find('.panel-heading strong.title').find('span').prop('outerHTML'));

var $executionCard = $('div.footerbar[data-module=execution]').closest('#flowBlock');
$executionCard.find('.panel-heading strong.title').attr('title', '<?php echo $lang->execution->common;?>');
$executionCard.find('.panel-heading strong.title').html('<?php echo $lang->execution->common;?> ' + $executionCard.find('.panel-heading strong.title').find('span').prop('outerHTML'));
</script>
