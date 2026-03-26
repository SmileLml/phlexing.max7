<?php
/**
 * The view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include '../../../common/ext/view/calendar.html.php'?>
<?php include $this->app->getModuleRoot() . 'user/view/featurebar.html.php';?>
<div id='mainContent'>
  <div class='calendar' id='calendar'>
    <header class="calender-header table-row">
      <div class="btn-toolbar col-4 table-col text-middle">
        <button type="button" class="btn btn-info btn-icon btn-mini btn-prev"><i class="icon-chevron-left"></i></button>
        <button type="button" class="btn btn-info btn-mini btn-today"><?php echo $lang->today;?></button>
        <button type="button" class="btn btn-info btn-icon btn-mini btn-next"><i class="icon-chevron-right"></i></button>
        <span class="calendar-caption"></span>
        <?php echo html::a($this->createLink('user', 'todo', "userID={$user->id}&type=all"), $lang->todo->all, '', "class='btn btn-link' id='todoTab'")?>
        <?php echo html::a($this->createLink('user', 'effort', "userID={$user->id}&type=all"), $lang->effort->all, '', "class='btn btn-link' id='effortTab'")?>
      </div>
      <div class="col-4 text-center table-col">
        <ul class="nav nav-primary">
          <?php if(common::hasPriv('user', 'todocalendar')):?>
          <li><?php echo html::a($this->createLink('user', 'todocalendar', "userID={$user->id}"), $lang->todo->common);?></li>
          <?php elseif(common::hasPriv('user', 'todo')):?>
          <li><?php echo html::a($this->createLink('user', 'todo', "userID={$user->id}"), $lang->todo->common);?></li>
          <?php endif;?>
          <li class="active"><?php echo html::a($this->createLink('user', 'effortcalendar', "userID={$user->id}"), $lang->effort->common);?></li>
        </ul>
      </div>
      <div class="col-4 table-col"></div>
    </header>
  </div>
</div>
<script language='javascript'>
$(document).ready(function() {
    var tasks   = <?php echo $efforts?>;

    $('#calendar').calendar({
        hideEmptyWeekends: true,
        dragThenDrop:false,
        data:
        {
            calendars:
            {
                defaultCal: {color: '#fff'}
            },
            events: tasks
        },
        eventCreator: function(event, $cell, calendar)
        {
          var $event = $('<div title="' + event.title + '" data-id="' + (event.id || '') + '" class="event has-time" title="' + (event.desc || '') + '"><span class="title">' + event.title + '</span><span class="time">' + event.consumed + 'h</span></div>');
          return $event;
        },
        clickEvent: function(event)
        {
            event = event.event;
            if(event.url)
            {
                var modalTrigger = new $.zui.ModalTrigger({width: '80%', url: event.url, type:'iframe'});
                modalTrigger.show();
            }
        }
    });
});
<?php if(common::hasPriv('effort', 'export')):?>
$(function()
{
    $('#featurebar .actions').prepend(<?php echo json_encode(html::a('javascript:exportCalendar("' . $this->createLink('effort', 'export', "userID={$user->id}&orderBy=date_asc,begin_asc&date=_date_") . '")', $lang->export, '', "class='btn'"));?>);
})
<?php endif;?>
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
