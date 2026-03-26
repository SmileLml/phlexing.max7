<style>
.priWidth{width:30px !important;}
<?php if($app->getClientLang() == 'zh-cn' || $app->getClientLang() == 'zh-tw'):?>
.dateWidth{width:80px !important; padding: 8px !important;}
.delayWidth{width:80px !important;}
.estWidth{width:65px !important; padding: 8px !important}
.taskConsumedWidth{width:85px !important;}
.taskTotalWidth{width:65px !important; padding: 8px !important;}
.projectConsumedWidth{width:80px !important; padding: 8px !important;}
.userConsumedWidth{width:80px !important; padding: 8px !important;}
<?php else:?>
.dateWidth{width:110px !important; padding: 8px !important;}
.delayWidth{width:80px !important;}
.estWidth{width:80px !important; padding: 8px !important;}
.taskConsumedWidth{width:110px !important;}
.taskTotalWidth{width:80px !important; padding: 8px !important;}
.projectConsumedWidth{width:120px !important; padding: 8px !important;}
.userConsumedWidth{width:110px !important; padding: 8px !important;}
<?php endif;?>
</style>
<div class='flex bg-canvas p-2 gap-3' id='conditions'>
  <div class='input-group w-1/4'>
    <span class='input-group-addon'><?php echo $lang->pivot->dept;?></span>
    <?php echo html::select('dept', $depts, $dept, "class='form-control chosen' onchange='changeParams()'");?>
  </div>
  <div class='input-group w-1/2'>
    <span class='input-group-addon'><?php echo $lang->pivot->taskAssignedDate;?></span>
    <div id='beginPicker' zui-create zui-create-datepicker="{defaultValue: '<?php echo $begin;?>', onChange: (e) => changeParams()}" ></div>
    <span class='input-group-addon'><?php echo $lang->pivot->to;?></span>
    <div id='endPicker' zui-create zui-create-datepicker="{defaultValue: '<?php echo $end;?>', onChange: (e) => changeParams()}"></div>
  </div>
</div>
<?php if(empty($userTasks)):?>
<div class="cell bg-canvas">
  <div class="dtable-empty-tip">
    <p><span class="text-muted"><?php echo $lang->error->noData;?></span></p>
  </div>
</div>
<?php else:?>
<?php global $config;?>
<div class='cell'>
  <div class='panel rounded ring-0 bg-canvas'>
    <div class="panel-heading">
      <div class="panel-title"><?php echo $title;?></div>
    </div>
    <div class='panel-body pt-0'>
      <div class='table-responsive' data-ride='table'>
        <table class='table table-condensed table-striped table-bordered table-fixed' id="worksummary">
          <thead>
            <tr class='colhead text-center bg-canvas'>
              <th class="w-20 border"><?php echo $lang->task->assignedTo;?></th>
              <?php if($config->systemMode == 'ALM'):?>
              <th class="border <?php echo common::checkNotCN() ? 'w-28' : 'w-20';?>"><?php echo $lang->task->project;?></th>
              <?php endif;?>
              <th class="border <?php echo common::checkNotCN() ? 'w-28' : 'w-20';?>"><?php echo $lang->task->execution;?></th>
              <th class="border w-14"><?php echo $lang->task->id;?></th>
              <th class="border <?php echo common::checkNotCN() ? 'w-28' : 'w-20';?>"><?php echo $lang->task->name;?></th>
              <th class="border priWidth"><?php echo $lang->priAB;?></th>
              <th class="border dateWidth"><?php echo $lang->task->estStarted;?></th>
              <th class="border dateWidth"><?php echo $lang->task->realStarted;?></th>
              <th class="border dateWidth"><?php echo $lang->task->deadline;?></th>
              <th class="border dateWidth"><?php echo $lang->task->assignedDate;?></th>
              <th class="border delayWidth"><?php echo $lang->pivot->delay . '(' . $lang->pivot->day . ')';?></th>
              <th class="border estWidth"><?php echo $lang->task->estimate;?></th>
              <th class="border <?php echo common::checkNotCN() ? 'w-28' : 'w-24'?>"><?php echo $lang->pivot->taskConsumed;?></th>
              <th class="border taskTotalWidth"><?php echo $lang->pivot->taskTotal;?></th>
              <th class="border projectConsumedWidth"><?php echo $lang->pivot->executionConsumed;?></th>
              <th class="border userConsumedWidth"><?php echo $lang->pivot->userConsumed;?></th>
            </tr>
          </thead>
          <tbody>
            <?php $color = false;?>
            <?php $i     = 0;?>
            <?php foreach($userTasks as $user => $projectTaskGroup):?>
            <?php if(!isset($users[$user])) continue;?>
            <?php
            $taskTotal         = $totalConsumed = 0;
            $executionConsumed = array();
            foreach($projectTaskGroup as $executionTasks)
            {
                foreach($executionTasks as $tasks)
                {
                    $taskTotal += count($tasks);
                    foreach($tasks as $task)
                    {
                        if(!isset($executionConsumed[$task->execution])) $executionConsumed[$task->execution] = 0;
                        $executionConsumed[$task->execution] += $task->consumed;
                        $totalConsumed += $task->consumed;
                    }
                }
            }
            ?>
            <tr class="text-center">
              <td class="border w-user" rowspan="<?php echo $taskTotal;?>"><?php echo zget($users, $user);?></td>
              <?php $j = 0;?>
              <?php foreach($projectTaskGroup as $projectID => $executionTasks):?>
              <?php
              $projectTaskTotal = count($executionTasks, 1) - count($executionTasks);
              if($j != 0) echo "<tr class='text-center'>";
              $projectName = zget($projects, $projectID, '');
              ?>
              <?php if($config->systemMode == 'ALM'):?>
              <td class='border text-left' rowspan="<?php echo $projectTaskTotal;?>" title='<?php echo $projectName?>'><?php echo $projectName;?></td>
              <?php endif;?>
              <?php $g = 0;?>
              <?php foreach($executionTasks as $executionID => $tasks):?>
              <?php
              $executionTaskTotal = count($tasks);
              if($g != 0) echo "<tr class='text-center'>";
              $executionName = zget($executions, $executionID, '');
              ?>
              <td class='border text-left' rowspan="<?php echo $executionTaskTotal;?>" title='<?php echo $executionName?>'><?php echo $executionName;?></td>
              <?php $k = 0;?>
              <?php foreach($tasks as $task):?>
              <?php if($k != 0) echo "<tr class='text-center'>"?>
              <td class='border'><?php echo $task->id;?></td>
              <td class="border text-left" title="<?php echo $task->name;?>">
                <?php
                if($task->parent > 0) echo "[{$lang->task->childrenAB}] ";
                if($task->multiple)   echo "[{$lang->task->multipleAB}] ";
                echo $task->name;
                ?>
              </td>
              <td class="border"><span class='<?php echo 'pri' . $task->pri?>'><?php echo $task->pri;?></span></td>
              <td class="border"><?php echo $task->estStarted;?></td>
              <td class="border"><?php echo substr($task->realStarted, 0, 10);?></td>
              <td class="border"><?php echo $task->deadline;?></td>
              <td class="border"><?php echo substr($task->assignedDate, 0, 10);?></td>
              <td class="border">
                <?php
                if(!helper::isZeroDate($task->deadline))
                {
                    $deadline   = strtotime($task->deadline);
                    $targetDate = !helper::isZeroDate($task->finishedDate) ? strtotime(substr($task->finishedDate, 0, 10)) : strtotime(helper::today());
                    $days       = round(($targetDate - $deadline) / 3600 / 24);
                    if($days > 0) echo $days;
                }
                ?>
              </td>
              <td class="border"><?php echo $task->estimate;?></td>
              <td class="border"><?php echo $task->consumed;?></td>
              <?php if($k == 0):?>
              <td class="border" rowspan="<?php echo $executionTaskTotal;?>"><?php echo $executionTaskTotal;?></td>
              <td class="border" rowspan="<?php echo $executionTaskTotal;?>"><?php echo zget($executionConsumed, $executionID, '');?></td>
              <?php endif;?>
              <?php if($j == 0):?>
              <td class="border" rowspan="<?php echo $taskTotal;?>"><?php echo $totalConsumed;?></td>
              <?php endif;?>
            </tr>
            <?php $i++; $j++; $k++; $g++;?>
            <?php endforeach;?>
            <?php endforeach;?>
            <?php endforeach;?>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
    <div class='pull-right p-2 no-morph' id='pagerWorkAssignSummary' zui-create zui-create-pager="window.pagerWorkAssignSummaryOptions"></div>
  </div>
</div>
<?php endif;?>
<script>
function changeParams()
{
    const beginPick = $('#beginPicker').zui();
    const endPick = $('#endPicker').zui();

    var dept  = $('#conditions').find('#dept').val();
    var begin = beginPick.$.value;
    var end   = endPick.$.value;
    if(begin.indexOf('-') != -1)
    {
        var beginarray = begin.split("-");
        var begin = '';
        for(i=0 ; i < beginarray.length ; i++)
        {
            begin = begin + beginarray[i];
        }
    }
    if(end.indexOf('-') != -1)
    {
        var endarray = end.split("-");
        var end = '';
        for(i=0 ; i < endarray.length ; i++)
        {
            end = end + endarray[i];
        }
    }

    var params = window.btoa('begin=' + begin + '&end=' + end + '&dept=' + dept);
    var link = $.createLink('pivot', 'preview', 'dimension=' + <?php echo $dimensionID?> + '&group=' + <?php echo $groupID;?> + '&method=workassignsummary&params=' + params);
    window.location.href = link;
}

function getLink(info)
{
    const beginPick = $('#beginPicker').zui();
    const endPick = $('#endPicker').zui();

    var dept  = $('#conditions').find('#dept').val();
    var begin = beginPick.$.value;
    var end   = endPick.$.value;
    if(begin.indexOf('-') != -1)
    {
        var beginarray = begin.split("-");
        var begin = '';
        for(i=0 ; i < beginarray.length ; i++)
        {
            begin = begin + beginarray[i];
        }
    }
    if(end.indexOf('-') != -1)
    {
        var endarray = end.split("-");
        var end = '';
        for(i=0 ; i < endarray.length ; i++)
        {
            end = end + endarray[i];
        }
    }

    var params = window.btoa('begin=' + begin + '&end=' + end + '&dept=' + dept + '&recTotal=' + info.recTotal + '&recPerPage=' + info.recPerPage + '&pageID=' + info.page);
    return $.createLink('pivot', 'preview', 'dimension=' + <?php echo $dimensionID?> + '&group=' + <?php echo $groupID;?> + '&method=workassignsummary&params=' + params);
}

window.pagerWorkAssignSummaryOptions = {
    items: [
        {type: 'info', text: '<?php echo str_replace('<strong>{recTotal}</strong>', $pager->recTotal, $lang->pager->totalCount);?>'},
        {type: 'size-menu', text: '<?php echo str_replace('<strong>{recPerPage}</strong>', $pager->recPerPage , $lang->pager->pageSize);?>', dropdown: {placement: 'top'}},
        {type: 'link', page: 'first', icon: 'icon-first-page', hint: '<?php echo $lang->pager->firstPage;?>'},
        {type: 'link', page: 'prev', icon: 'icon-angle-left', hint: '<?php echo $lang->pager->previousPage;?>'},
        {type: 'info', text: '<?php echo $pager->pageID;?>/<?php echo $pager->pageTotal;?>'},
        {type: 'link', page: 'next', icon: 'icon-angle-right', hint: '<?php echo $lang->pager->nextPage;?>'},
        {type: 'link', page: 'last', icon: 'icon-last-page', hint: '<?php echo $lang->pager->lastPage;?>'},
    ],
    page: <?php echo $pager->pageID;?>,
    recTotal: <?php echo $pager->recTotal;?>,
    recPerPage: <?php echo $pager->recPerPage;?>,
    linkCreator: (info) => {return getLink(info);}
};
</script>
