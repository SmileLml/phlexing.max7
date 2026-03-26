<?php $budget = $this->loadModel('workestimation')->getBudget($project->id);?>
<?php $projectCost = empty($budget) ? zget($this->config->custom, 'cost', 1) : $budget->unitLaborCost;?>
<div id='projectCost'><?php echo empty($projectCost) ? 0 : $ac * $projectCost;?></div>
<script>
$('.projectCost').html($('#projectCost').html());
$('#projectCost').empty();
</script>
