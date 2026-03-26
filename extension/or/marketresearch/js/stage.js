$(function()
{
    $("#" + status + "Tab").addClass('btn-active-text');

    /* Update table summary text. */
    $('#stageForm').table(
    {
        statisticCreator: function(table)
        {
            var $table       = table.getTable();
            var $originTable = table.isDataTable ? table.$.find('.datatable-origin') : null;
            var $rows        = $table.find(table.isDataTable ? '.datatable-rows .datatable-row-left' : 'tbody>tr');

            var stageCount      = 0;
            var childStageCount = 0;
            var taskCount       = 0;
            var unclosedCount   = 0;
            var waitCount       = 0;
            var doingCount      = 0;
            var stageIDList     = [];
            $rows.each(function()
            {
                var $row = $(this);
                if($originTable) $row = $originTable.find('tbody>tr[data-id="' + $row.data('id') + '"]');

                var data = $row.data();
                stageIDList.push(data.id);

                if(data.type === 'stage' && data.nestPathLevel === 1) stageCount++;
                if(data.type === 'stage' && data.nestPathLevel > 1)   childStageCount++;
                if(data.type === 'task' && data.status === 'wait')    waitCount++;
                if(data.type === 'task' && data.status === 'doing')   doingCount++;
                if(data.type === 'task' && data.status !== 'closed')  unclosedCount++;
                if(data.type === 'task')                              taskCount++;
            });

            if(status != 'all') return summary.format(stageCount, childStageCount, taskCount);
            return allSummary.format(stageCount, childStageCount, taskCount, unclosedCount, waitCount, doingCount);
        }
    })
})

window.addEventListener('scroll', this.handleScroll)
function handleScroll(e)
{
    var relative = 200; // 相对距离
    $('tr.showmore').each(function()
    {
        var $showmore = $(this);
        var offsetTop = $showmore[0].offsetTop;
        if(offsetTop == 0) return true;

        if(getScrollTop() + getWindowHeight() >= offsetTop - relative)
        {
            throttle(loadData($showmore), 150)
        }
    })
}

function loadData($showmore)
{
    $showmore.removeClass('showmore');

    var stageID = $showmore.attr('data-parent');
    var maxTaskID   = $showmore.attr('data-id');
    var maxTaskID   = maxTaskID.replace('t', '');
    var link = createLink('task', 'ajaxGetTasks', 'stageID=' + stageID + '&maxTaskID=' + maxTaskID);
    $.get(link, function(data)
    {
        $showmore.after(data);
        $(".iframe").modalTrigger({type:'iframe'});

        $('#stageForm').table('initNestedList');
    })
}

function throttle(fn, threshhold)
{
    var last;
    var timer;
    threshhold || (threshhold = 250);

    return function()
    {
        var context = this;
        var args = arguments;

        var now = +new Date()

        if (last && now < last + threshhold)
        {
            clearTimeout(timer);
            timer = setTimeout(function ()
            {
                last = now
                fn.apply(context, args)
            }, threshhold)
        }
        else
        {
            last = now
            fn.apply(context, args)
        }
    }
}

function getScrollTop()
{
    return scrollTop = document.body.scrollTop + document.documentElement.scrollTop
}

function getWindowHeight()
{
    return document.compatMode == "CSS1Compat" ? windowHeight = document.documentElement.clientHeight : windowHeight = document.body.clientHeight
}

/**
 * Set the color of the badge to white.
 *
 * @param  object  obj
 * @param  bool    isShow
 * @access public
 * @return void
 */
function setBadgeStyle(obj, isShow)
{
    var $label = $(obj);
    if(isShow == true)
    {
        $label.find('.label-badge').css({"color":"#fff", "border-color":"#fff"});
    }
    else
    {
        $label.find('.label-badge').css({"color":"#838a9d", "border-color":"#838a9d"});
    }
}
