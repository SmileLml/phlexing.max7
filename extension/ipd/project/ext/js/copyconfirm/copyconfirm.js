$(document).ready(function()
{
    var copyProject = JSON.parse(sessionStorage.getItem('project'));
    $.each(copyProject, function(key, project)
    {
        if(project.name.indexOf('products[') != -1)
        {
            hiddenHtml = "<input type='hidden' name='" + project.name + "' id='" + project.name + "' value='" + project.value + "'>";
        }
        else if(project.name.indexOf('plans[') != -1)
        {
            hiddenHtml = "<input type='hidden' name='" + project.name + "' id='" + project.name + "' value='" + project.value + "'>";
        }
        else if(project.name.indexOf('whitelist[') != -1)
        {
            hiddenHtml = "<input type='hidden' name='" + project.name + "' id='" + project.name + "' value='" + project.value + "'>";
        }
        else if(project.name.indexOf('branch[') != -1)
        {
            hiddenHtml = "<input type='hidden' name='" + project.name + "' id='" + project.name + "' value='" + project.value + "'>";
        }
        else if(project.name.indexOf('isLinkStory[') != -1)
        {
            hiddenHtml = "<input type='hidden' name='project[isLinkStory]' id='project[isLinkStory]' value='" + project.value + "'>";
        }
        else
        {
            hiddenHtml = "<input type='hidden' name='project[" + project.name + "]' id='project[" + project.name + "]' value='" + project.value + "'>"
        }

        $("#submit").before(hiddenHtml);
    });

    $("table tr").on('click', '.has-info', function(e)
    {
        $(this).removeClass('has-info');
    });
});

/**
 * Load stages when change product.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function loadStages(obj)
{
    var productID  = $(obj).val();
    var $panelBody = $(obj).closest('.waterfallstage').find('.waterfallbody');
    var loadURL    = createLink('project', 'ajaxLoadStages', 'projectID=' + copyProjectID + '&productID=' + productID + '&copyToProduct=' + productID);
    $panelBody.load(loadURL, function()
    {
        $panelBody.find('.form-date').datepicker();
        $panelBody.find('[name^=PMs]').chosen();
    });
}

/**
 * Convert a date string like 2011-11-11 to date object in js.
 *
 * @param  string $date
 * @access public
 * @return date
 */
function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    return new Date(dateString[0], dateString[1] - 1, dateString[2]);
}

/**
 * Compute delta of two days.
 *
 * @param  string $date1
 * @param  string $date1
 * @access public
 * @return int
 */
function computeDaysDelta(date1, date2)
{
    date1 = convertStringToDate(date1);
    date2 = convertStringToDate(date2);
    delta = (date2 - date1) / (1000 * 60 * 60 * 24) + 1;

    weekEnds = 0;
    for(i = 0; i < delta; i++)
    {
        if((weekend == 2 && date1.getDay() == 6) || date1.getDay() == 0) weekEnds ++;
        date1 = date1.valueOf();
        date1 += 1000 * 60 * 60 * 24;
        date1 = new Date(date1);
    }
    return delta - weekEnds;
}

/**
 * Compute work days.
 *
 * @access public
 * @return void
 */
function computeWorkDays(currentID)
{
    index = currentID.replace(/\w*\[|\]/g, '');
    beginDate = $('#begins\\[' + index + '\\]').val();
    endDate   = $('#ends\\[' + index + '\\]').val();

    if(beginDate && endDate)
    {
        $('#dayses\\[' + index + '\\]').val(computeDaysDelta(beginDate, endDate));
    }
}

function changeType(obj)
{
    const $this = $(obj);
    const $row  = $this.closest('tr');
    const type  = $this.val();

    $nextRow = $row.next();
    while($nextRow.length > 0)
    {
        if($row.attr('data-level') >= $nextRow.attr('data-level')) break;

        let $childType = $nextRow.find('[name^=attributes]');
        $childType.toggleClass('select-disabled', type != 'mix');
        if(type != 'mix') $childType.val(type);

        $nextRow = $nextRow.next();
    }
}
