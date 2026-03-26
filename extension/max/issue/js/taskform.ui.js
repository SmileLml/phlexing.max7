window.waitDom('#resolvePanel input[name=execution]', function(){loadAll()});
window.loadAll = function()
{
    const executionID = $('#resolvePanel input[name=execution]').val();
    if(!executionID) return;

    const moduleID = $('#resolvePanel input[name=module]').val();
    loadModules(executionID);
    loadExecutionMembers(executionID);
}

function loadModules(executionID)
{
    if(executionID instanceof Event) executionID = $('#resolvePanel input[name=execution]').val();

    const extra         = $('#resolvePanel input[name=showAllModule]').prop('checked') ? 'allModule' : '';
    const getModuleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + executionID + '&viewtype=task&branch=0&rootModuleID=0&returnType=items&fieldID=&extra=' + extra);
    $.getJSON(getModuleLink, function(modules)
    {
        const $modulePicker = $('#resolvePanel input[name=module]').zui('picker');
        $modulePicker.render({items: modules});
    });
}

function loadExecutionMembers(executionID)
{
    const getAssignedToLink = $.createLink('execution', 'ajaxGetMembers', 'executionID=' + executionID + '&assignedTo=' + $('#assignedTo').val());
    $.getJSON(getAssignedToLink, function(members)
    {
        const $assignedToPicker = $('input[name=assignedTo]').zui('picker');
        $assignedToPicker.render({items: members});

        $('#modalTeam [data-name=team] input[name^=team]').each(function()
        {
            let $memberPicker = $(this).zui('picker');
            $memberPicker.render({items: members});
        });
    });
}

window.renderRowData = function($row, index, row)
{
    const mode = $('[name=mode]').val();
    $row.find('[data-name=id]').addClass('center').html("<span class='team-number'>" + $row.find('[data-name=id]').text() + "</span><i class='icon-angle-down " + (mode == 'linear' ? '' : 'hidden') + "'><i/>");

    const executionID       = $('#resolvePanel input[name=execution]').val();
    const getAssignedToLink = $.createLink('execution', 'ajaxGetMembers', 'executionID=' + executionID + '&assignedTo=' + $('#assignedTo').val());
    $.getJSON(getAssignedToLink, function(members)
    {
        let $memberPicker = $row.find('[data-name=team] input[name^=team]').zui('picker');
        $memberPicker.render({items: members});
    });
}

/**
 * 根据多人任务是否勾选展示团队。
 * Show team menu box.
 *
 * @access public
 * @return void
 */
function toggleTeam()
{
    const $assignedToBox = $('.assignedToBox');
    if($('[name^=multiple]').prop('checked'))
    {
        $assignedToBox.find('.add-team').removeClass('hidden');
        $assignedToBox.find('.picker-box').addClass('hidden');
        $assignedToBox.find('.assignedToList').removeClass('hidden');
        $('input[name=estimate]').attr('disabled', true);
    }
    else
    {
        $assignedToBox.find('.add-team').addClass('hidden');
        $assignedToBox.find('.picker-box').removeClass('hidden');
        $assignedToBox.find('.assignedToList').addClass('hidden');
        $('input[name=estimate]').removeAttr('disabled');
    }
}

$('#teamTable .team-saveBtn').on('click.team', '.btn', function()
{
    $('div.assignedToList').html('');

    let team            = [];
    let totalEstimate   = 0;
    let error           = false;
    let mode            = $('[name="mode"]').val();
    let assignedToList  = '';

    $(this).closest('#teamTable').find('.picker-box').each(function(index)
    {
        if(!$(this).find('[name^=team]').val()) return;

        let realname = $(this).find('.picker-single-selection').text();
        let account  = $(this).find('[name^=team]').val();
        if(!team.includes(realname)) team.push(realname);

        let estimate = parseFloat($(this).closest('tr').find('[name^=teamEstimate]').val());
        if(!isNaN(estimate) && estimate > 0) totalEstimate += estimate;

        if(realname != '' && (isNaN(estimate) || estimate <= 0))
        {
            zui.Modal.alert(realname + ' ' + estimateNotEmpty);
            error = true;
            return false;
        }

        assignedToList += `<div class='picker-multi-selection' data-index=${index}><span class='text'>${realname}</span><div class="picker-deselect-btn btn size-xs ghost"><span class="close"></span></div></div>`;
        if(mode == 'linear') assignedToList += '<i class="icon icon-arrow-right"></i>';
    })

    if(error) return false;

    if(team.length < 2)
    {
        zui.Modal.alert(teamMemberError);
        return false;
    }
    else
    {
        $('[data-name=estimate] input').val(totalEstimate);
    }

    /* 将选中的团队成员展示在指派给后面. */
    const regex = /<i class="icon icon-arrow-right"><\/i>(?!.*<i class="icon icon-arrow-right"><\/i>)/;
    assignedToList = assignedToList.replace(regex, '');
    $('div.assignedToList').prepend(assignedToList);

    zui.Modal.hide('#modalTeam');
    return false;
})

$('#resolveFrom').on('click', '.assignedToList .picker-multi-selection', function()
{
    /* 团队成员必须大于1人. */
    if($(this).closest('.assignedToList').find('.picker-multi-selection').length == 2)
    {
        zui.Modal.alert(teamMemberError);
        return false;
    }

    /* 删除人员前后的箭头. */
    if($(this).next('.icon').length)
    {
        $(this).next('.icon').remove();
    }
    else if($(this).prev('.icon').length)
    {
        $(this).prev('.icon').remove();
    }

    $(this).remove();

    /* 删除团队中，已经选中的人. */
    let index = $(this).data('index');
    $('#teamTable').find('tr').eq(index).remove();

    let totalEstimate = 0;

    $('#teamTable').find('[name^=teamEstimate]').each(function(index)
    {
        let estimate = parseFloat($(this).val());
        if(!isNaN(estimate) && estimate > 0) totalEstimate += estimate;
    })

    $("[name='estimate']").val(totalEstimate);

    setLineIndex();
})

/**
 * Set line number.
 *
 * @access public
 * @return void
 */
function setLineIndex()
{
    let index = 1;
    $('.team-number').each(function()
    {
        $(this).text(index);
        $(this).closest('tr').find('[id^="line"]').attr('id', 'line' + index);
        index ++;
    });
}

/* 切换串行/并行 展示/隐藏工序图标. */
$('#modalTeam').on('change.team', '[name="mode"]', function()
{
    if($(this).val() == 'multi')
    {
        $('#teamTable td .icon-angle-down').addClass('hidden');
    }
    else
    {
        $('#teamTable td .icon-angle-down').removeClass('hidden');
    }
});

/**
 * 根据任务类型设置任务相关字段。
 * Set task-related fields based on the task type.
 *
 * @param  object e
 * @access public
 * @return void
 */
function typeChange()
{
    const result = $('#resolveFrom [name=type]').val();

    /* Change assigned person to multiple selection, and hide multiple team box. */
    const $assignedToPicker = $('#resolveFrom [name^=assignedTo]').zui('picker');
    if(result == 'affair')
    {
        const $assignedToBox = $('.assignedToBox');
        $assignedToBox.find('.add-team').addClass('hidden');
        $assignedToBox.find('.picker-box').removeClass('hidden');
        $assignedToBox.find('.assignedToList').addClass('hidden');
        $('input[name=estimate]').removeAttr('disabled');
        $('[name=multiple]').prop("checked", false);

        $assignedToPicker.render({multiple: true, checkbox: true, toolbar: true});

    }
    /* If assigned selection is multiple, remove multiple and hide the selection of select all members. */
    else if($assignedToPicker.options.multiple)
    {
        $assignedToPicker.render({multiple: false, checkbox: false, toolbar: false});
        $assignedToPicker.$.setValue('');
    }

    $('#resolveFrom [name=multiple]').closest('.checkbox-primary').toggleClass('hidden', result == 'affair');
}
