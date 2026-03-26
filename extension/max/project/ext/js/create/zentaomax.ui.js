$(function()
{
    $('.copy-project-btn').attr('data-size', 'md');
    $('.all-content .all-data').addClass('checked');
    setProgram('program');

    if(pageType == 'copy' && multiple == 1) $('#form-project-' + rawMethod + ' [type=submit]').text(nextStepText);
    if(copyType == 'previous')
    {
        if(end == LONG_TIME)
        {
            $('[data-name=begin] [name=longTime]').trigger('click');
        }
        else
        {
            $('[name=end]').zui('datePicker').$.setValue(end);
        }
        if(future == 'on')
        {
            $('[data-name=budget] [name=future]').trigger('click');
        }
        else
        {
            $('[data-name=budget] [name=budget]').val(budget);
        }
        if(days > 0 && end && end != LONG_TIME)
        {
            setTimeout(function()
            {
                $('[data-name=days] [name=days]').val(days);
            }, 100)
        }
    }
})

window.setProgram = function(type)
{
    const programID       = $('#copyProjectModal [name=parent]').length > 0 ? $('#copyProjectModal [name=parent]').val() : 0;
    const projectID       = (type == 'project') ? $('#copyProjectModal [name=selectCopyprojectID]').val() : 0;
    const loadProjectLink = $.createLink('project', 'ajaxLoadProject', 'programID=' + programID + '&projectID=' + projectID + '&model=' + model);
    loadTarget(loadProjectLink, '#replaceList');

    if(type == 'program')
    {
        const getProjecttLink = $.createLink('project', 'ajaxGetProjects', 'programID=' + programID + '&model=' + model);
        $.getJSON(getProjecttLink, function(projects)
        {
            const $selectCopyprojectID = $('#copyProjectModal [name=selectCopyprojectID]').zui('picker');
            $selectCopyprojectID.render({items: projects});
            $selectCopyprojectID.$.setValue(projectID);

            $('#tabContent1 .footer-btns .btn.next-btn').removeAttr('disabled');
            if(projects.length == 0)
            {
                $('#tabContent2 [name=choseCopyprojectID]').val('');
                $('#tabContent1 .footer-btns .btn.next-btn').attr('disabled', 'disabled');
            }
        });
    }
}

$(document).on('click', '#replaceList .project-block', function(e)
{
    const $projectBlock = $(e.target).closest('.project-block');
    $('#replaceList button.project-block.primary-outline').removeClass('primary-outline');
    $projectBlock.addClass('primary-outline');
})

$(document).on('change', '#copyProjectModal [name=parent]', function(){setProgram('program')});
$(document).on('change', '#copyProjectModal [name=selectCopyprojectID]', function(){setProgram('project');});
$(document).on('change', '.copy-container li:nth-child(2) a', function(e){e.stopPropagation();});

$(document).on('click', '#tabContent1 .next-btn', function()
{
    const $projectBlock = $('#replaceList .project-block.primary-outline');
    $('#tabContent2 .copy-project-title').html('<i class="icon icon-project"></i>' + $projectBlock.data('title'));
    $('#tabContent2 .copy-project-title').attr('title', $projectBlock.data('title'));
    $('#tabContent2 [name=choseCopyprojectID]').val($projectBlock.data('id'));

    /* Remove all data item when copy no multiple project. */
    $('#tabContent1').removeClass('active in');
    $('#tabContent2').addClass('active in');
    $('#tabContent2 .all-content [name=allCheckbox]').trigger('click');

    let multiple = $('#tabContent1 .project-block.primary-outline').attr('data-multiple') == 1;
    $('#tabContent2 .all-content .normal-data').toggleClass('hidden', !multiple);
    $('#tabContent2 .all-content .no-sprint-data').toggleClass('hidden', multiple);

    $('.copy-container li:first-child').removeClass('active');
    $('.copy-container li:nth-child(2)').addClass('active');

});

$(document).on('click', '#tabContent2 .complete-btn', function()
{
    const copyProjectID = $('#tabContent2 [name=choseCopyprojectID]').val();
    if($('#tabContent2 [name=basicCheckbox]').prop("checked"))
    {
        loadPage($.createLink('project', 'create', `model=${model}&programID=${programID}&copyProjectID=${copyProjectID}&extra=copyType=part&pageType=base`));
    }
    else
    {
        loadPage($.createLink('project', 'create', `model=${model}&programID=${programID}&copyProjectID=${copyProjectID}&extra=copyType=all&pageType=copy`));
    }
    zui.Modal.hide();
})

$(document).on('click', '.copy-project-btn', function()
{
    $('#tabContent1').addClass('active in');
    $('#tabContent2').removeClass('active in');
    $('#tabContent2 .all-content').removeClass('hidden');
    $('#tabContent2 [name=allCheckbox], #tabContent2 [name=basicCheckbox]').prop('checked', false);

    $('.copy-container li:first-child').addClass('active');
    $('.copy-container li:nth-child(2)').removeClass('active');
})

$(document).on('click', '#tabContent2 .all-content [name=allCheckbox]', function()
{
    $('#tabContent2 [name=basicCheckbox]').prop('checked', false);
    dataToggleClass('remove', '.basic-content .all-data');
    dataToggleClass('add', '.all-content .all-data');
})

$(document).on('click', '#tabContent2 .basicData [name=basicCheckbox]', function()
{
    $('#tabContent2 [name=allCheckbox]').prop('checked', false);
    dataToggleClass('remove', '.all-content .all-data');
    dataToggleClass('add', '.basic-content .all-data');
})

function dataToggleClass(action, className)
{
    if (action == 'add')
    {
        $(className).addClass('checked')
    }
    else
    {
        $(className).removeClass('checked')
    }
}

window.removeAllTips = function()
{
    $('.has-warning').removeClass('has-warning');
    $('.text-warning').remove();

    if(copyType == 'all' || copyType == 'previous')
    {
        /* Add project sessionStorage. */
        sessionStorage.setItem("project", JSON.stringify(convertToArray($('form').serialize())));
    }
}

window.convertToArray = function(data)
{
    let result = [];
    $.each(data.split('&'), function(index, pair)
    {
      let parts = pair.split('=');
      result.push({
        name: parts[0].trim(),
        value: parts[1] ? decodeURIComponent(parts[1].replace(/\+/g, ' ')).trim() : ''
      });
    });
    return result;
}
