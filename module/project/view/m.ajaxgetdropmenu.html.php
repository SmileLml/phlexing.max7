<div class='heading divider'>
  <span class='title'>
    <input type='text' class='input' id='search' value='' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>
  </span>
  <nav class='nav'>
    <a data-dismiss='display'><i class='icon-remove muted'></i></a>
  </nav>
</div>
<?php js::set('projectID', $projectID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<div id='searchResult' class='content'>
  <div id='defaultMenu' class='search-list'>
    <div id='activedItems'>
      <?php
      $mines    = array();
      $others   = array();
      $dones    = array();
      $link     = $this->createLink('project', 'execution', "status=all&projectID=%s");
      foreach($projects as $programID => $programProjects)
      {
          foreach($programProjects as $project)
          {
              if($project->status != 'done' and $project->PM == $this->app->user->account) $mines[$programID][$project->id] = $project;
              if($project->status != 'done' and !($project->PM == $this->app->user->account)) $others[$programID][$project->id] = $project;
              if($project->status == 'done') $dones[$programID][$project->id] = $project;
          }
      }

      if($mines)
      {
          echo "<li class='heading'>{$lang->project->mine}</li>";
          echo "<div class='list'>";
          foreach($mines as $programID => $projects)
          {
              foreach($projects as $project)
              {
                  $projectName = zget($programs, $project->parent, '') ? zget($programs, $project->parent) . '/' . $project->name : $project->name;
                  echo html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i>&nbsp;{$projectName}", '', "class='mine text-important item' data-id='{$project->id}' data-tag=':{$project->status} @{$project->PM} @mine' data-key='{$project->code}'");
              }
          }
          echo '</div>';
      }

      if($others)
      {
          echo "<div class='heading'>{$lang->project->other}</div>";
          $class = "other";
          echo "<div class='list'>";
          foreach($others as $programID => $projects)
          {
              foreach($projects as $project)
              {
                  $projectName = zget($programs, $project->parent, '') ? zget($programs, $project->parent) . '/' . $project->name : $project->name;
                  echo html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i>&nbsp;{$projectName}", '', "class='$class item' data-id='{$project->id}' data-tag=':{$project->status} @{$project->PM}' data-key='{$project->code}'");
              }
          }
          echo '</div>';
      }

      if($dones)
      {
          echo "<div class='heading'>{$lang->project->doneExecutions}</div>";
          $class = "other";
          echo "<div class='list'>";
          foreach($dones as $programID => $projects)
          {
              foreach($projects as $project)
              {
                  $projectName = zget($programs, $project->parent, '') ? zget($programs, $project->parent) . '/' . $project->name : $project->name;
                  if($project->status == 'done') echo html::a(sprintf($link, $project->id), "<i class='icon-folder-close-alt'></i> {$projectName}", '', "data-id='{$project->id}' data-tag=':{$project->status} @{$project->PM}' data-key='{$project->code}' class='done item'");
              }
          }
          echo '</div>';
      }
      ?>
    </div>
  </div>
</div>
