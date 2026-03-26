<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     execution
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include '../../../common/ext/view/calendar.html.php'?>
<style>
#date {float: left; margin-right: 10px; margin-left: 0px;}
</style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    foreach($this->lang->execution->featureBar['effortcalendar'] as $period => $label)
    {
        if($period != 'calendar' and !common::hasPriv('execution', 'effort')) break;
        $method = $period == 'calendar' ? 'effortcalendar' : 'effort';
        $vars   = $period == 'calendar' ? "executionID=$executionID" : "executionID=$executionID&date=$period";
        $active = $period == 'calendar' ? 'btn-active-text' : '';
        $label  = "<span class='text'>$label</span>";
        echo html::a(inlink($method, $vars), $label, '', "class='btn btn-link $active' id='{$period}'");
    }
    ?>
    <div class='input-control space w-150px'>
      <?php echo html::select('account', $users, $userID, "onchange='changeUser($executionID, this.value)' class='form-control chosen'");?>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php if(common::hasPriv('effort', 'export')) echo html::a('javascript:void(0)', "<i class='icon-export muted'></i> " . $lang->effort->export, '', "class='btn btn-link' onclick=exportCalendar('" . helper::createLink('effort', 'export', "userID=$userID&orderBy=date_desc&date=_date_&executionID=$executionID") . "')") ?>
  </div>
</div>
<div class="main-row">
  <div class="main-col">
    <div class="cell">
      <div id="effortCalendar" class="calendar">
        <header class="calender-header table-row">
          <div class="btn-toolbar col-4 table-col text-middle">
            <button type="button" class="btn btn-info btn-mini btn-today"><?php echo $lang->today;?></button>
            <button type="button" class="btn btn-info btn-icon btn-mini btn-prev"><i class="icon-chevron-left"></i></button>
            <span id="date" class="calendar-caption"></span>
            <button type="button" class="btn btn-info btn-icon btn-mini btn-next"><i class="icon-chevron-right"></i></button>
          </div>
          <div class="col-4 text-center table-col"></div>
          <div class="col-4 table-col"></div>
        </header>
      </div>
    </div>
  </div>
</div>
<script>
config.ajaxGetEffortsUrl = '<?php echo $this->createLink('execution', 'ajaxGetEfforts', "executionID=$executionID&userID=$userID&year={year}");?>';
config.effortViewUrl     = '<?php echo $this->createLink('effort', 'view', 'id={id}', '', true);?>';
config.textNetworkError  = '<?php echo $lang->textNetworkError;?>';
config.textHasMoreItems  = '<?php echo $lang->textHasMoreItems;?>';

var effortViewModalTrigger = new $.zui.ModalTrigger(
{
    width: '70%',
    type: 'iframe',
    rememberPos: 'effortViewModal',
    waittime: 5000
});

var displayDate = 0;
var calendar    = false;
$(function()
{

    var expandedDays = {};
    var minExpandCount = 6;
    var $calendar = $('#effortCalendar');
    var toggleLoading = function(loading)
    {
        $calendar.toggleClass('loading', !!loading);
    };
    calendar = $calendar.calendar(
    {
        dragThenDrop: false,
        hideEmptyWeekends: true,
        data:
        {
            events: [],
            calendars:
            {
                defaultCal: {color: '#fff'}
            }
        },
        beforeDisplay: function(display, doDisplay)
        {
            var date = display.date;
            var thisDisplayDate = date.getFullYear();
            if (displayDate === thisDisplayDate)
            {
                return doDisplay();
            }
            else
            {
                displayDate = thisDisplayDate;
            }
            var calendar = this;
            toggleLoading(true);
            $.ajax(
            {
                url: config.ajaxGetEffortsUrl.replace('{year}', date.getFullYear()),
                dataType: 'json',
                success: function(data)
                {
                    $.each(data, function(index, effort)
                    {
                        effort.allDay   = !effort.end;
                        effort.consumed = Number(effort.consumed).toFixed(2);
                    });
                    calendar.resetData({events: data});
                    doDisplay();
                },
                error: function()
                {
                    $.zui.messager.danger(config.textNetworkError);
                },
                complete: function() {toggleLoading(false);}
            });
            return false;
        },
        eventCreator: function(event, $cell, calendar)
        {
          var $event = $('<div title="' + event.divTitle + '" data-id="' + (event.id || '') + '" class="event has-time" title="' + (event.desc || '') + '"><span class="title">' + event.title + '</span><span class="time">' + event.consumed + 'h</span></div>');
          return $event;
        },
        dayFormater: function($cell, date, dayEvents, calendar)
        {
            if(dayEvents && dayEvents.maxPos >= minExpandCount)
            {
                var hideManyEvents = !expandedDays[date.toDateString()];
                $cell.toggleClass('hide-many-events', hideManyEvents);
                if(hideManyEvents)
                {
                    var $cellContent = $cell.find('.day > .content');
                    var $showMore = $cellContent.find('.show-more-events');
                    if(!$showMore.length)
                    {
                        $showMore = $('<div class="show-more-events" />').appendTo($cellContent);
                    }
                    else
                    {
                        $showMore.show();
                    }
                    $showMore.text(config.textHasMoreItems.format(dayEvents.maxPos - minExpandCount + 1));
                }
            }
            else
            {
                $cell.removeClass('hide-many-events');
            }
        },
        eventSorter: function(a, b)
        {
            var result = a.start - b.start;
            if (result === 0) {
                return a.id - b.id;
            }
            return result;
        }
    }).data('zui.calendar');
	$calendar.on('click', '.show-more-events', function(e)
	{
		var $cell = $(this).hide().closest('.cell-day');
		$cell.removeClass('hide-many-events');
		expandedDays[$cell.find('.day').attr('data-date')] = true;
		e.stopPropagation();
	}).on('click', '.event', function(e)
	{
		var event = $(this).data('event');
        eventUrl = event.url == '' ? config.effortViewUrl.replace('{id}', event.id) : event.url;
		effortViewModalTrigger.show({url: eventUrl});
		e.stopPropagation();
	});

});

function changeUser(executionID, userID)
{
    location.href = createLink('execution', 'effortcalendar', 'executionID=' + executionID + '&userID=' + userID);
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
