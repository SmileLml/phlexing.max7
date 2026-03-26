<?php
/**
 * The finish mobile view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(!empty($task->team) && $task->assignedTo != $this->app->user->account):?>
<div class='heading'>
  <div class='title'><strong><?php echo $lang->task->tips;?></strong></div>
  <nav class='nav'><a data-dismiss='display'><i class='icon-remove muted'></i></a></nav>
</div>
<div class='content article box'>
  <p><?php echo sprintf($lang->task->deniedNotice, '<strong>' . $task->assignedToRealName . '</strong>', $lang->task->finish);?></p>
</div>
<?php else:?>
<div class='heading divider'>
  <span class='title'><strong><?php echo $lang->task->finish;?></strong> #<?php echo $task->id . ' ' . $task->name;?></span>
  <nav class='nav'>
    <a data-dismiss='display'><i class='icon-remove muted'></i></a>
  </nav>
</div>
<form class='has-padding content' method='post' target='hiddenwin' action='<?php echo $this->createLink('task', 'finish', "taskID=$task->id")?>' id='finishForm' data-form-refresh='#page' enctype='multipart/form-data'>
  <div class='control'>
    <label for='hasConsumed'><?php echo !empty($task->team) ? $lang->task->common . $lang->task->consumed : $lang->task->hasConsumed;?></label>
    <?php echo $task->consumed . $lang->workingHour;?>
  </div>
  <?php if(!empty($task->team)):?>
  <div class='control'>
    <label for='hasConsumed'><?php echo $lang->task->hasConsumed;?></label>
    <?php echo (float)$task->myConsumed;?> <?php echo $lang->workingHour;?>
  </div>
  <?php endif;?>
  <div class='control'>
    <label for='currentConsumed'><?php echo $lang->task->currentConsumed;?></label>
    <?php echo html::input('currentConsumed', 0, "class='input'");?>
  </div>
  <div class='control'>
    <label for='totalConsumed'><?php echo empty($task->team) ? $lang->task->consumed : $lang->task->myConsumed;?></label>
    <?php $consumed = empty($task->team) ? $task->consumed : (float)$task->myConsumed;?>
    <?php 
    echo "<span id='totalConsumed'>" . (float)$consumed . "</span> " . $lang->workingHour . html::hidden('consumed', $consumed);
    js::set('consumed', $consumed);
    ?>
  </div>
  <div class='control'>
    <label for='assignedTo'><?php echo empty($task->team) ? $lang->task->assign : $lang->task->transferTo;?></label>
    <div class='select'><?php echo html::select('assignedTo', $members, $task->openedBy);?></div>
  </div>
  <div class='control'>
    <label for='realStarted'><?php echo $lang->task->realStarted;?></label>
    <input type='date' class='input' id='realStarted' name='realStarted' value='<?php echo $task->realStarted != '0000-00-00 00:00:00' ? $task->realStarted : '';?>'>
  </div>
  <div class='control'>
    <label for='finishedDate'><?php echo $lang->task->finishedDate;?></label>
    <input type='date' class='input' id='finishedDate' name='finishedDate' value='<?php echo helper::today()?>'>
  </div>
  <div class='control'>
    <?php echo $this->fetch('file', 'buildForm', 'fileCount=1');?>
  </div>
  <div class='control'>
    <label for='comment'><?php echo $lang->comment;?></label>
    <?php echo html::textarea('comment', '', 'rows=2 class="textarea"');?></td>
  </div>
</form>
<div class='footer has-padding'>
  <button type='button' id='submitButton' class='btn primary'><?php echo $lang->save ?></button>
</div>

<script>
$(function()
{
    $('#submitButton').click(function(){$('#finishForm').submit()});
    $(function()
    {
        $('#currentConsumed').keyup(function()
        {   
            var currentConsumed = $(this).val();
            if(!parseInt(currentConsumed)) currentConsumed = 0;
            var totalConsumed = parseInt(currentConsumed) + parseInt(consumed);
            $('#totalConsumed').html(totalConsumed);
            $('#consumed').val(totalConsumed);
        });
    });
})
</script>
<?php endif;?>
