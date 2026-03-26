/**
 * Load product projects.
 *
 * @param  int productID
 * @access public
 * @return void
 */
function loadProductProject(productID)
{
    if(productID == 0)
    {
        replaceProject(defaultProject, true);
        loadProductExecutions(productID, $('#project').val());
        return;
    }

    var project = $('#project').length > 0 ? $('#project').val() : 0;
    var link = createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&branch=0&project=' + project + '&pageType=old');

    $.get(link, function(data)
    {
        replaceProject(data);
        $('#projectIdBox select').trigger('change');
    });
}

/**
 * Load product、project executions.
 *
 * @param  int $productID
 * @access public
 * @return void
 */
function loadProductExecutions(productID, projectID)
{
    if($('#project').length > 0)
    {
        if(productID == 0 && projectID == 0)
        {
            replaceExecution(defaultExecution, true);
            return;
        }

        if(productID == 0)
        {
            var link = createLink('project', 'ajaxGetExecutions', 'projectID=' + projectID + '&mode=multiple,leaf&type=all&pageType=old');
        }
        else
        {
            var link = createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&project=' + $('#project').val() + '&branch=0&pageType=old&executionID=' + $('#executionIdBox #execution').val() + '&from=&mode=multiple,leaf');
        }
    }
    else // In classic mode.
    {
        if(productID == 0)
        {
            replaceExecution(defaultExecution);
            return;
        }

        var link = createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&project=0&branch=0&pageType=old&executionID=' + $('#executionIdBox #execution').val());
    }

    $.get(link, function(data)
    {
        replaceExecution(data);
    });
}

/**
 * Replace project data.
 *
 * @param  string data
 * @access public
 * @return void
 */
function replaceProject(data, emptySelect)
{
    $('#projectIdBox select').replaceWith(data);
    $('#projectIdBox .chosen-container').remove();
    $('#projectIdBox .picker').remove();
    if($('#projectIdBox select option').length > maxCount)
    {
        $('#projectIdBox select').picker();
    }
    else
    {
        $('#projectIdBox select').chosen();
    }

    if(typeof(emptySelect) != 'undefined') $('#projectIdBox select').val('').trigger('chosen:updated');
}

/**
 * Replace execution data.
 *
 * @param  string data
 * @access public
 * @return void
 */
function replaceExecution(data, emptySelect)
{
    $('#executionIdBox select').replaceWith(data);
    $('#executionIdBox .chosen-container').remove();
    $('#executionIdBox .picker').remove();
    if($('#executionIdBox select option').length > maxCount)
    {
        $('#executionIdBox select').picker();
    }
    else
    {
        $('#executionIdBox select').chosen();
    }

    if(typeof(emptySelect) != 'undefined') $('#executionIdBox select').val('').trigger('chosen:updated');
}

/**
 * Load dept users.
 *
 * @param  int    $deptID
 * @param  string $key    id|account
 * @access public
 * @return void
 */
function loadDeptUsers(deptID, key)
{
    if(typeof(key) == 'undefined') key = 'id';
    var link = createLink('dept', 'ajaxGetUsers', 'dept=' + deptID + '&user=' + $('#userBox #user').val() + '&key=' + key);

    $.get(link, function(data)
    {
        $('#userBox select').replaceWith(data);
        $('#userBox .chosen-container').remove();
        $('#userBox select').chosen();
    })
}

/**
 * Flush width.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function flushWidth(obj)
{
    var maxWidth = 0;
    $(obj).find('.input-group').each(function()
    {
        var $groupAddon = $(this).find('.input-group-addon:first');
        if($groupAddon.length > 0)
        {
            var width = $(this).find('.input-group-addon:first').outerWidth();
            if(width > maxWidth) maxWidth = width;
        }
    });
    $(obj).find('.input-group').each(function()
    {
        var $groupAddon = $(this).find('.input-group-addon:first');
        var padding     = 1;
        if($groupAddon.length > 0)
        {
            while($groupAddon.outerWidth() < maxWidth)
            {
                $groupAddon.css('padding-right', padding + 'px').css('padding-left', padding + 'px');
                padding++;
            }
        }
    });
}

/**
 * Fix scroll.
 *
 * @access public
 * @return void
 */
function fixScroll()
{
    var $scrollwrapper = $('div.datatable').first().find('.scroll-wrapper:first');
    if($scrollwrapper.length == 0)return;

    var $tfoot       = $('div.table-footer');
    var scrollOffset = $scrollwrapper.offset().top + $scrollwrapper.find('.scroll-slide').height();
    if($tfoot.length > 0) scrollOffset += $tfoot.height();
    if($('div.datatable.head-fixed').length == 0) scrollOffset -= '29';
    var windowH = $(window).height();
    var bottom  = 25 + $tfoot.height();
    if(scrollOffset > windowH + $(window).scrollTop())
    {
        $scrollwrapper.css({'position': 'fixed', 'bottom': bottom + 'px'});
        $tfoot.addClass('fixed-footer');
    }

    $(window).scroll(function()
    {
        if(scrollOffset <= windowH + $(window).scrollTop())
        {
            $scrollwrapper.css({'position':'relative', 'bottom': '0px'});
            $tfoot.removeClass('fixed-footer');
        }
        else if($scrollwrapper.css('position') != 'fixed')
        {
            if(!$tfoot.hasClass('fixed-footer')) $tfoot.addClass('fixed-footer');
            $scrollwrapper.css({'position': 'fixed', 'bottom': bottom + 'px'});
        }
    });
}
