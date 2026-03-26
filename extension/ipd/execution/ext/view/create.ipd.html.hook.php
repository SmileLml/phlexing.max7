<?php if($project and $project->model == 'ipd' and $plan):?>
<?php
$parents        = $this->loadModel('execution')->getPairs($projectID, 'stage', 'all');
$executionTasks = $this->execution->getTaskGroupByExecution(array_keys($parents));

foreach($executionTasks as $executionID => $tasks)
{
    if(isset($parents[$executionID])) unset($parents[$executionID]);
}

$parentBox  = '<tr class="parentBox ">';
$parentBox .= '  <th class="w-120px">' . $lang->execution->parentStage . '</th>';
$parentBox .= '  <td class="col-main required">';
$parentBox .= html::select('parent', $parents, '', "class='form-control chosen' onchange='loadType(this.value)'");
$parentBox .= '  </td>';
$parentBox .= '  <td colspan="2"></td>';
$parentBox .= '</tr>';
?>
<script>

$(function()
{
    $('#project').closest('tr').after(<?php echo json_encode($parentBox);?>);
    $('#parent').chosen();
    $('#attribute').parents('tr').remove();
    $('#type').removeAttr('onchange');
});

function loadType(parentID)
{
    $('#type').load(createLink('execution', 'ajaxGetTypes', 'parentID=' + parentID), function(data)
    {
        $('#type_chosen').remove();
        $('#type').replaceWith(data);
        $('#type').chosen();
    });

}
</script>
<?php endif;?>
