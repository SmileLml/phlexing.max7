<div class='heading divider'>
  <span class='title'>
    <input type='text' class='input' id='search' value='' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>
  </span>
  <nav class='nav'>
    <a data-dismiss='display'><i class='icon-remove muted'></i></a>
  </nav>
</div>
<?php js::set('executionID', $executionID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<div id='searchResult' class='content'>
  <div id='defaultMenu' class='search-list'>
    <div id='activedItems'>
      <?php
      $mines  = array();
      $others = array();
      $dones  = array();
      foreach($executions as $projectID => $projectExecutions)
      {
          foreach($projectExecutions as $execution)
          {
              if($execution->status != 'done' and $execution->PM == $this->app->user->account) $mines[$projectID][$execution->id] = $execution;
              if($execution->status != 'done' and !($execution->PM == $this->app->user->account)) $others[$projectID][$execution->id] = $execution;
              if($execution->status == 'done') $dones[$projectID][$execution->id] = $execution;
          }
      }

      if($mines)
      {
          echo "<li class='heading'>{$lang->execution->mine}</li>";
          echo "<div class='list'>";
          foreach($mines as $projectID => $executions)
          {
              foreach($executions as $execution) echo html::a(sprintf($link, $execution->id), "<i class='icon-folder-close-alt'></i>&nbsp;{$execution->name}", '', "class='mine text-important item' data-id='{$execution->id}' data-tag=':{$execution->status} @{$execution->PM} @mine' data-key='{$execution->code}'");
          }
          echo '</div>';
      }

      if($others)
      {
          echo "<div class='heading'>{$lang->execution->other}</div>";
          $class = "other";
          echo "<div class='list'>";
          foreach($others as $projectID => $executions)
          {
              foreach($executions as $execution) echo html::a(sprintf($link, $execution->id), "<i class='icon-folder-close-alt'></i>&nbsp;{$execution->name}", '', "class='$class item' data-id='{$execution->id}' data-tag=':{$execution->status} @{$execution->PM}' data-key='{$execution->code}'");
          }
          echo '</div>';
      }

      if($dones)
      {
          echo "<div class='heading'>{$lang->execution->doneExecutions}</div>";
          echo "<div class='list'>";
          foreach($dones as $projectID => $executions)
          {
              foreach($executions as $execution) echo html::a(sprintf($link, $execution->id), "<i class='icon-folder-close-alt'></i> {$execution->name}", '', "data-id='{$execution->id}' data-tag=':{$execution->status} @{$execution->PM}' data-key='{$execution->code}' class='done item'");
          }
          echo '</div>';
      }
      ?>
    </div>
  </div>
</div>
