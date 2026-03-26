<?php
/**
 * The back mobile view file of leave module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     leave 
 * @version     $Id
 * @link        http://www.zdoo.com
 */

if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>

<div class='heading divider'>
  <div class='title'><i class='icon icon-plue'></i> <strong><?php echo $lang->leave->back;?></strong></div>
  <nav class='nav'><a data-dismiss='display'><i class='icon icon-remove'></i></a></nav>
</div>

<form class='content box' id='backLeaveForm' data-form-refresh='#page' method='post' action='<?php echo $this->createLink('leave', 'back', "leaveID={$leave->id}");?>'>
  <div class='control'>
    <label for='backDate'><?php echo $lang->leave->backDate;?></label>
    <input type='datetime-local' class='input' id='backDate' name='backDate' value="<?php echo formatTime($leave->backDate);?>">
  </div>
</form>

<div class='footer has-padding'>
  <button type='button' data-submit='#backLeaveForm' class='btn primary'><?php echo $lang->save;?></button>
</div>

<script>
$(function()
{
    $('#backLeaveForm').modalForm().listenScroll({container: 'parent'});
})
</script>
