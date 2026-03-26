<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-group pull-left">
  </div>
  <?php if($projectID && common::canModify($from, $object)):?>
  <div class='pull-right'><?php echo html::a($this->createLink('pssp', 'update', "projectID=$projectID&from=$from&executionID=$executionID"), $lang->pssp->update, '', "class='btn btn-primary' data-app=$app->tab");?></div>
  <?php endif;?>
  <div class='btn-group pull-lift'>
  <?php if($app->tab != 'execution' and $model != 'waterfall' and $model != 'waterfallplus' and !empty($object->multiple)):?>
     <?php $viewName = $executions[$executionID];?>
     <a href='javascript:;' class='btn btn-link btn-limit' data-toggle='dropdown'><span class='text' title='<?php echo $viewName;?>'><?php echo $viewName;?></span> <span class='caret'></span></a>
     <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
     <?php
     foreach($executions as $key => $execution)
     {
        echo "<li>" . html::a(inlink('browse', "projectID=$projectID&from=$from&browseType=$key"), $execution, '', "title='{$execution}' class='text-ellipsis'") . "</li>";
     }
     endif;
     ?>
     </ul>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-table'>
    <table class='table table-bordered has-sort-head table-fixed'>
      <thead>
        <tr>
          <th><?php echo $lang->pssp->processType;?></th>
          <th><?php echo $lang->pssp->processName;?></th>
          <th><?php echo $lang->pssp->activityName;?></th>
          <th><?php echo $lang->pssp->activityReason;?></th>
          <th><?php echo $lang->pssp->result;?></th>
          <th><?php echo $lang->pssp->outputName;?></th>
          <th><?php echo $lang->pssp->outputReason;?></th>
          <th><?php echo $lang->pssp->result;?></th>
        </tr>
      </thead>
      <tbody class='sortable'>
        <?php $groupStarted = false;?>
        <?php foreach($processList as $type => $group):?>
          <?php $processes = $group['processList'];?>
          <?php if(!$groupStarted) echo "<tr>";?>
          <?php if(!$groupStarted) $groupStarted = true;?>
          <td rowspan="<?php echo $group['rows'];?>"><?php echo zget($lang->process->$classify, $type);?> </td>
          <?php $processStarted = true;?>
          <?php $processEnded   = false;?>

          <?php foreach($processes as $process):?>
          <?php if(!$processStarted) echo '<tr>';?>

          <td rowspan="<?php echo $process->outputNum ? $process->outputNum : 1;?>"><?php echo $process->name;?></td>
          <?php if(!$processStarted) $processStarted = true;?>

          <?php if(empty($process->activityList)):?>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          </tr>
          <?php continue;?>
          <?php endif;?>

          <?php $isFirstActivity = true;?>
          <?php $activityEnded   = false;?>
          <?php foreach($process->activityList as $activity):?>
          <?php if(!$isFirstActivity) echo '<tr>';?>
          <?php $isFirstActivity = false;?>
          <?php if($activity == end($process->activityList)) $activityEnded = true;?>
          <?php $activeRows = empty($activity->outputList) ? 1 : count($activity->outputList);?>
          <td rowspan='<?php echo $activeRows;?>'>
            <?php if(!empty($activity->tailorNorm)):?>
            <div class="tip" style='display: inline-block'><icon class='icon icon-help' data-toggle='popover' data-container='body' data-trigger='focus hover' data-placement='top' data-tip-class='text-muted popover-sm' data-content=<?php echo $activity->tailorNorm;?>></icon></div>
            <?php endif;?>
            <span title='<?php echo $activity->name;?>'><?php echo $activity->name;?></span>
          </td>
          <td rowspan='<?php echo $activeRows;?>'><?php echo isset($activity->reason) ? $activity->reason : '';?></td>
          <td rowspan='<?php echo $activeRows;?>'><?php echo isset($activity->result) ? zget($lang->pssp->resultList, $activity->result, '') : '';?></td>

          <?php if(empty($activity->outputList)):?>
          <td></td>
          <td></td>
          <td></td>
          <?php $processStarted = false;?>
          </tr>
          <?php continue;?>
          <?php endif;?>

          <?php $isFirstOutput = true;?>
          <?php $outputEnded   = false;?>

          <?php foreach($activity->outputList as $output):?>
          <?php if(!$isFirstOutput) echo '<tr>';?>
          <?php $isFirstOutput = false;?>
          <td>
            <?php if(!empty($output->tailorNorm)):?>
            <div class="tip" style='display: inline-block'><icon class='icon icon-help' data-toggle='popover' data-container='body' data-trigger='focus hover' data-placement='top' data-tip-class='text-muted popover-sm' data-content=<?php echo $output->tailorNorm;?>></icon></div>
            <?php endif;?>
            <span title='<?php echo $output->name;?>'><?php echo $output->name;?>
          </td>
          <td><?php echo isset($output->reason) ? $output->reason : '';?></td>
          <td><?php echo isset($output->result) ? zget($lang->pssp->resultList, $output->result) : '';?></td>
          </tr>
          <?php continue;?>
          <?php endforeach;?>
          <?php endforeach;?>
          <?php endforeach;?>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
<script>$('[data-toggle="popover"]').popover();</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
