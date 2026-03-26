<table class="table table-bordered basicInfo">
  <tbody>
    <tr>
      <td rowspan='3'><strong><?php echo $lang->milestone->common;?></strong></td>
      <th><?php echo $lang->project->name;?></th>
      <td colspan='3'><?php echo $basicInfo->project->name;?></td>
      <th><?php echo $lang->execution->end;?></th>
      <td><?php echo $basicInfo->execution->end;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->project->PM;?></th>
      <td><?php echo zget($users, $basicInfo->project->PM);?></td>
      <th><?php echo $lang->milestone->name;?></th>
      <td><?php echo $basicInfo->execution->name;?></td>
      <th><?php echo $lang->project->realEnd;?></th>
      <td><?php echo $basicInfo->execution->realEnd;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->milestone->startedWeeks;?>
        <i class="icon icon-help" title="<?php echo $lang->milestone->startedWeekTip;?>"></i>
      </th>
      <td><?php echo $basicInfo->execution->startedWeeks;?></td>
      <th><?php echo $lang->milestone->finishedWeeks;?>
        <i class="icon icon-help" title="<?php echo $lang->milestone->finishedWeekTip;?>"></i>
      </th>
      <td><?php echo $basicInfo->execution->finishedWeeks;?></td>
      <th><?php echo $lang->milestone->offset;?></th>
      <td><?php echo $basicInfo->execution->offset;?></td>
    </tr>
  </tbody>
</table>
