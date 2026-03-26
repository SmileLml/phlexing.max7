<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<nav id='menu' class='menu nav affix dock-top canvas'>
  <?php
  if(!isset($type))   $type   = 'today';
  if(!isset($period)) $period = 'today';
  $date = isset($date) ? $date : helper::today();

  echo  $userList;
  echo  html::a($this->createLink('user', 'todo',      "userID={$user->id}"), $lang->user->todo, '', "id='todoTab'");
  echo  html::a($this->createLink('user', 'effort',    "userID={$user->id}"), $lang->user->effort, '', "id='effortTab'");
  echo  html::a($this->createLink('user', 'story',     "userID={$user->id}"), $lang->user->story, '', "id='storyTab'");
  echo  html::a($this->createLink('user', 'task',      "userID={$user->id}"), $lang->user->task, '', "id='taskTab'");
  echo  html::a($this->createLink('user', 'bug',       "userID={$user->id}"), $lang->user->bug, '', "id='bugTab'");
  echo  html::a($this->createLink('user', 'testtask',  "userID={$user->id}"), $lang->user->test, '', "id='testtaskTab'");
  echo  html::a($this->createLink('user', 'dynamic',   "userID={$user->id}"), $lang->user->dynamic, '', "id='dynamicTab'");
  echo  html::a($this->createLink('user', 'execution', "userID={$user->id}"), $lang->user->execution, '', "id='executionTab'");
  echo  html::a($this->createLink('user', 'profile',   "userID={$user->id}"), $lang->user->profile, '', "id='profileTab'");
  ?>
  <a class='moreMenu hidden' data-display='dropdown' data-placement='beside-bottom'><?php echo $lang->more;?></a>
  <div id='moreMenu' class='list dropdown-menu'></div>
</nav>
<script>
var type   = '<?php echo $type;?>';
var period = '<?php echo $period;?>';
</script>
