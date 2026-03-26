<table class='table bordered'>
  <thead>
    <tr>
      <th class='c-name'><?php echo $lang->project->name;?></th>
      <th class='w-80px'><?php echo $lang->project->PM;?></th>
      <th class='w-60px'><?php echo $lang->project->status;?></th>
      <th class='w-90px text-right'><?php echo $lang->task->consumed;?></th>
      <th class='w-80px text-right'><?php echo $lang->project->budget;?></th>
      <th class='w-80px'><?php echo $lang->project->leftStories;?></th>
      <th class='w-80px'><?php echo $lang->project->leftTasks;?></th>
      <th class='w-80px'><?php echo $lang->project->leftBugs;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($projects as $project):?>
    <?php $viewLink = $this->createLink('project', 'view', "programID={$project->id}");?>
    <tr data-id='<?php echo $project->id;?>' data-url='<?php echo $viewLink;?>'>
      <td title='<?php echo $project->name?>'><?php echo $project->name;?></td>
      <td><?php echo zget($users, $project->PM, $project->PM)?></td>
      <td class='c-status'>
        <span class="status-program status-<?php echo $project->status?>"><?php echo zget($lang->project->statusList, $project->status);?></span>
      </td>
      <td class='text-right' title="<?php echo $project->consumed . ' ' . $lang->execution->workHour;?>"><?php echo $project->consumed . $lang->execution->workHourUnit;?></td>
      <?php $programBudget = in_array($this->app->getClientLang(), ['zh-cn','zh-tw']) ? round((float)$project->budget / 10000, 2) . $this->lang->project->tenThousand : round((float)$project->budget, 2);?>
      <td class='text-right'><?php echo $project->budget != 0 ? zget($lang->project->currencySymbol, $project->budgetUnit) . ' ' . $programBudget : $lang->project->future;?></td>
      <td class='text-center'><?php echo $project->leftStories;?></td>
      <td class='text-center'><?php echo $project->leftTasks;?></td>
      <td class='text-center'><?php echo $project->leftBugs;?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
