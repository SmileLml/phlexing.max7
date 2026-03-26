$(function()
{
    var $tasksTable = $('#tasksTable');
    if(!rowsCount) return $tasksTable.removeClass('loading');

    var $tableContainer     = $('#tableContainer');
    var $tableHeader        = $('#tableHeader');
    var $tableFooter        = $('#tableFooter');
    var $tableBody          = $('#tableBody');
    var $cells              = $('#cells');
    var $scrollbarContainer = $('#scrollbarContainer');
    var $timeline           = $('#timeline');
    var $timeList           = $('#timeList');
    var $totalDays          = $('#totalDays');
    var $scrollbar          = $('#scrollbar');
    var $window             = $(window);
    var cellWidth           = 56;
    var dayWidth            = cellWidth * 2;
    var lastScrollLeft      = 0;
    var lastScrollTop       = 0;
    var lastTimelineWidth;
    var isHeaderFixed        = false;
    var isFooterFixed        = false;
    var winHeight            = $window.height();
    var headerHeight         = $tableHeader.outerHeight();
    var footerHeight         = $tableFooter.outerHeight();
    var pageFooterHeight     = $('#footer').outerHeight();
    var currentYear          = new Date().getFullYear();
    var firstVisibleDayIndex = 0;
    var rowLayouts           = [];
    var maxRowHeight         = 0;
    var lastHoverRow, lastHoverDay, hoverDelayTimer;


    /* Init body bounds */
    for (var i = 0; i < groupsCount; ++i)
    {
        var $group = $('#group-' + i);
        var height = $group.outerHeight();
        $group.find('.group-tasks').css('min-height', height - 1);
    }
    bodyBounds = $.extend({}, $tableBody[0].getBoundingClientRect());

    /* Init layout of rows */
    for (var i = 0; i < rowsCount; ++i)
    {
        var $task  = $('#task-' + i);
        var bounds = $task[0].getBoundingClientRect();
        var layout = {top: bounds.top - bodyBounds.top, height: bounds.height, bottom: bounds.bottom - bodyBounds.top};

        rowLayouts.push(layout);
        maxRowHeight = Math.max(maxRowHeight, layout.height);
    }

    var scrollTopOnLoad = $window.scrollTop();
    if(scrollTopOnLoad > 0)
    {
        bodyBounds.y      += scrollTopOnLoad;
        bodyBounds.top    += scrollTopOnLoad;
        bodyBounds.bottom += scrollTopOnLoad;
    }

    /* Init layout of cells */
    var cellsWidth = dayWidth * days.length;
    $scrollbar.css('width', cellsWidth);
    $timeList.css('width', cellsWidth);
    $totalDays.css('width', cellsWidth);

    /* Layout timeline */
    function layoutTimeline(force)
    {
        var scrollLeft    = $scrollbarContainer.scrollLeft();
        var timeLineWidth = $timeline.width();

        if(!force && lastScrollLeft === scrollLeft && timeLineWidth === lastTimelineWidth) return;

        lastScrollLeft       = scrollLeft;
        lastTimelineWidth    = timeLineWidth;
        firstVisibleDayIndex = Math.floor(scrollLeft / dayWidth);

        var index        = firstVisibleDayIndex;
        var visibleWidth = timeLineWidth + dayWidth;
        var width        = 0;
        var $days        = $timeList.children('.day-cell').addClass('expired');
        var $tDays       = $totalDays.children('.day-cell').addClass('expired');

        while(width < visibleWidth && days[index])
        {
            var day  = days[index];
            var $day = $('#day-' + index);

            if($day.length) $day.removeClass('expired');
            else
            {
                $day = $(
                [
                    '<div class="day-cell" id="day-' +  index + '" data-day="' + index + '">',
                        '<div class="day-name">' + (day.indexOf(currentYear + '-') === 0 ? day.substr(5) : day) + '</div>',
                        '<div class="day-consumed">' + consumedText + '</div>',
                        '<div class="day-left">' + leftText + '</div>',
                    '</div>'
                ].join('')).css({left: index * dayWidth, width: dayWidth}).appendTo($timeList);
            }

            var $totalDay = $('#day-total-' + index);

            if($totalDay.length) $totalDay.removeClass('expired');
            else
            {
                var totalCounts = counts[day];
                $totalDay = $(
                [
                    '<div class="day-cell" id="day-total-' +  index + '" data-day="' + index + '">',
                        '<div class="day-consumed">' + (totalCounts ? totalCounts.countConsumed.toFixed(1) : '') + '</div>',
                        '<div class="day-left">' + (totalCounts ? totalCounts.countLeft.toFixed(1) : '') + '</div>',
                    '</div>'
                ].join('')).css({left: index * dayWidth, width: dayWidth}).appendTo($totalDays);
            }
            width += dayWidth;
            index++;
        }
        $days.filter('.expired').remove();
        $tDays.filter('.expired').remove();
        $timeList.css('left', 0 -scrollLeft);
        $totalDays.css('left', 0 -scrollLeft);
    }

    /* Layout table cells */
    function layoutCells(scrollTop)
    {
        if(typeof scrollTop !== 'number') scrollTop = $window.scrollTop();

        var firstVisibleRowIndex = 0;
        if(scrollTop > (bodyBounds.top - headerHeight))
        {
            for(var i = 0; i < rowLayouts.length; ++i)
            {
                var layout = rowLayouts[i];
                firstVisibleRowIndex++;
                if(scrollTop < layout.bottom + bodyBounds.top) break;
            }
        }

        var $oldCellList  = $cells.children('.data-cell').addClass('expired');
        var height        = 0;
        var visibleHeight = winHeight - headerHeight - pageFooterHeight - footerHeight + maxRowHeight;
        var rowIndex      = firstVisibleRowIndex < 3 ? 0 : firstVisibleRowIndex;

        while(height < visibleHeight && rowLayouts[rowIndex])
        {
            var layout       = rowLayouts[rowIndex];
            var dayIndex     = firstVisibleDayIndex;
            var visibleWidth = lastTimelineWidth + dayWidth;
            var width        = 0;
            var task         = taskList[rowIndex];

            while(width < visibleWidth && days[dayIndex])
            {
                var day   = days[dayIndex];
                var $cell = $('#cell-' + rowIndex + '-' + dayIndex);

                if($cell.length) $cell.removeClass('expired');
                else
                {
                    var data     = task ? task[day] : null;
                    var consumed = (data ? data.consumed : '') || '';
                    var left     = (data ? data.left : '') || '';

                    $cell = $(
                    [
                        '<div class="data-cell" id="cell-' + rowIndex + '-' + dayIndex + '" data-row="' + rowIndex + '" data-day="' + dayIndex + '">',
                            '<div class="day-consumed">' + consumed + '</div>',
                            '<div class="day-left">' + left + '</div>',
                        '</div>'
                    ].join(''));
                    $cell.css(
                    {
                        left:   dayIndex * dayWidth,
                        top:    layout.top + (rowIndex ? 1 : 0),
                        width:  dayWidth,
                        height: layout.height + (rowIndex ? 0 : 1)
                    }).appendTo($cells);
                }
                width += dayWidth;
                dayIndex++;
            }
            height += layout.height;
            rowIndex++;
        }

        $oldCellList.filter('.expired').remove();
        $cells.css('left', 0 -lastScrollLeft);
    }

    /* Layout table cells */
    function fixedHeaderFooter(scrollTop, force)
    {
        if(typeof scrollTop !== 'number')
        {
            force     = scrollTop;
            scrollTop = $window.scrollTop();
        }
        var needFixedHeader = scrollTop > (bodyBounds.top - headerHeight);
        if(force || needFixedHeader !== isHeaderFixed)
        {
            isHeaderFixed = needFixedHeader;
            $tableHeader.toggleClass('is-fixed', isHeaderFixed);
            $tableContainer.css('padding-top', isHeaderFixed ? headerHeight : 0);
            $tableHeader.css(isHeaderFixed ? {left: bodyBounds.left, width: bodyBounds.width} : {left: 'auto', width: 'auto'});
        }

        var needFixedFooter = (scrollTop + winHeight - pageFooterHeight) < bodyBounds.bottom;
        if(force || needFixedFooter !== isFooterFixed)
        {
            isFooterFixed = needFixedFooter;
            if(isFooterFixed)
            {
                $('#scrollbarContainer').css('top', '-12px');
                $('#scrollbarContainer').css('bottom', 'unset');
            }
            else
            {
                $('#scrollbarContainer').css('top', 'unset');
                $('#scrollbarContainer').css('bottom', '0');
            }
            $tableFooter.toggleClass('is-fixed', isFooterFixed);
            $tableContainer.css('padding-bottom', isFooterFixed ? footerHeight : 0);
            $tableFooter.css(isFooterFixed ? {left: bodyBounds.left, width: bodyBounds.width, bottom: pageFooterHeight} : {left: 'auto', width: 'auto', bottom: 0});
        }
    }

    /* Listen events to refresh layout */
    $scrollbarContainer.on('scroll', function()
    {
        layoutTimeline(true);
        layoutCells(lastScrollTop);
    });
    $window.on('scroll', function()
    {
        var scrollTop = $window.scrollTop();
        if(scrollTop === lastScrollTop) return;
        lastScrollTop = scrollTop;
        fixedHeaderFooter(scrollTop);
        layoutCells(scrollTop);
        clearHoverEffections();
    }).on('resize', function()
    {
        var bounds = $tableBody[0].getBoundingClientRect();
        bodyBounds.width = bounds.width;
        bodyBounds.left  = bounds.left;
        bodyBounds.right = bounds.right;
        bodyBounds.x     = bounds.x;
        winHeight        = $window.height();

        fixedHeaderFooter(true);
        layoutTimeline(true);
        clearHoverEffections();
    });

    /* Clear hover effections */
    function clearHoverEffections(row, day)
    {
        if(row !== false && lastHoverRow > -1)
        {
            $tableContainer.find('.hover-row').removeClass('hover-row');
            lastHoverRow = -1;
        }
        if(day !== false && lastHoverDay > -1)
        {
            lastHoverDay = -1;
            $tableContainer.find('.hover-day').removeClass('hover-day');
        }
    };

    /* Update hover effections */
    function updateHoverEffections()
    {
        var $cell = $(this);
        var row   = $cell.data('row');
        var day   = $cell.data('day');

        if(row !== lastHoverRow)
        {
            clearHoverEffections(1, false);
            $tableContainer.find('.day-cell,.data-cell,.group-task').filter('[data-row="' + row + '"]').addClass('hover-row');
            lastHoverRow = row;
        }
        if(day !== lastHoverDay)
        {
            clearHoverEffections(false, 1);
            $tableContainer.find('.day-cell,.data-cell,.group-task').filter('[data-day="' + day + '"]').addClass('hover-day');
            lastHoverDay = day;
        }
        hoverDelayTimer = 0;
    }

    /* Add mouse hover effections */
    $tableContainer.on('mouseenter', '.day-cell,.data-cell,.group-task', function()
    {
        if(hoverDelayTimer) clearTimeout(hoverDelayTimer);
        hoverDelayTimer = setTimeout(updateHoverEffections.bind(this), 20);
    }).on('mouseleave', '.day-cell,.data-cell,.group-task', clearHoverEffections);

    /* Init layouts */
    fixedHeaderFooter(true);
    layoutTimeline(true);
    layoutCells();

    /* Remove loading status */
    $tasksTable.removeClass('loading');

    $('#saveButton').on('click', function(){
        let begin = $('#begin').val();
        let end   = $('#end').val();

        if(begin > end) return alert(beginMoreThanEnd);
        if(end > today) return alert(endMoreThanToday);

        begin = begin.replace(/-/g, '');
        end   = end.replace(/-/g, '');

        const link = $.createLink('execution', 'computeTaskEffort', 'begin=' + begin + '&end=' + end);
        $.get(link, function(data){
            if(data == 'success') location.reload();
        });
    });
});
