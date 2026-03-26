<div id='dataWrapper'>
  <style>
  p.todo{margin-bottom:2px; overflow:hidden;}
  .effort-list {padding-left: 25px; overflow: hidden;}
  .datatable-span.flexarea > .datatable-wrapper {border-right: 1px solid #ddd; overflow: hidden;}
  .outer .table tbody td{vertical-align:top;}
  .outer .datatable {border: none}
  .datatable .table-bordered th {border-bottom: none}
  .table-footer {margin-top:10px;}
  </style>
  <table id='datatable' class="calendar table table-condensed table-hover table-bordered table-striped table-fixed">
    <thead>
      <tr class="text-center">
        <th width="100" data-width="100" data-merge-rows='true'><nobr><?php echo $lang->company->dept;?></nobr></th>
        <th width="100" data-width="100" data-merge-rows='true'><nobr><?php echo $lang->company->user;?></nobr></th>
        <?php foreach($days as $day) echo '<th class="flex-col" data-width="200"><nobr>' . $day . '</nobr></th>';?>
      </tr>
    </thead>
    <?php $userCount = 0;?>
    <?php foreach($datas as $deptID => $deptData):?>
      <?php foreach($deptData as $account => $userData):?>
      <?php if(empty($userData)) continue;?>
      <?php $userCount ++;?>
      <tr valign="top">
        <td valign="middle">
          <nobr>
          <?php $parentDept = isset($depts[$parent]) ? $depts[$parent] : $lang->company->noDept;?>
          <?php echo isset($depts[$deptID]) ? $depts[$deptID] : $parentDept;?>
          </nobr>
        </td>
        <td valign="middle"><nobr><?php echo zget($users, $account);?></nobr></td>
        <?php foreach($days as $day):?>
        <td>
        <nobr>
        <?php
        if(isset($userData[$day]))
        {
            if($type == 'todo')
            {
                foreach($userData[$day] as $work)
                {
                    echo "<p class='todo' title='{$work['todo']}'>";
                    echo (empty($work['begin'])) ? "<span style='margin-right:75px;'></span>" : '<span>' . $work['begin'] . '~' . $work['end'] . '</span> ';
                    echo $work['todo'] . '</p>';
                }
            }
            else
            {
                echo '<ol class="effort-list">';
                $canView  = common::hasPriv('effort', 'view');
                foreach($userData[$day] as $work)
                {
                    $work['consumed'] = helper::formatHours($work['consumed']);

                    /* Space for indentation.*/
                    $title = "{$work['work']} ({$work['consumed']}h)";
                    echo "<li title='$title'>";
                    echo $canView ? html::a(helper::createLink('effort', 'view', "id={$work['id']}&from=my", '', true), $title, '', "class='iframe'") : $work['work'];
                    echo "</li>";
                }
                echo '</ol>';
            }
        }
        ?>
        </nobr>
        </td>
        <?php endforeach;?>
      </tr>
      <?php unset($deptData[$account]);?>
      <?php endforeach;?>
      <?php foreach($deptData as $account => $userData):?>
      <?php $userCount ++;?>
      <tr valign="top">
        <?php $parentDept = isset($depts[$parent]) ? $depts[$parent] : $lang->company->noDept;?>
        <td valign="middle"><nobr><?php echo isset($depts[$deptID]) ? $depts[$deptID] : $parentDept;?></nobr></td>
        <td valign="middle"><nobr><?php echo zget($users, $account);?></nobr></td>
        <?php foreach($days as $day):?>
        <td></td>
        <?php endforeach;?>
      </tr>
      <?php endforeach;?>
    <?php endforeach;?>
  </table>
  <div class='table-footer'>
    <div class='table-statistic'><?php printf($lang->company->pageUserCount, $userCount);?></div>
    <?php if(isset($pager)):?>
    <?php
    $rawBegin = $this->app->rawParams['begin'];
    $rawEnd   = $this->app->rawParams['end'];
    $this->app->rawParams['begin']  = date('Ymd', $rawBegin);
    $this->app->rawParams['end']    = date('Ymd', $rawEnd);
    $this->app->rawParams['iframe'] = 'no';

    echo $pager->show('right', 'pagerjs');

    $this->app->rawParams['begin']  = $rawBegin;
    $this->app->rawParams['end']    = $rawEnd;
    $this->app->rawParams['iframe'] = 'yes';
    ?>
    <?php endif;?>
  </div>
</div>
