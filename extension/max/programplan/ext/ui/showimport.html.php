<?php
namespace zin;

include $app->getModuleRoot() . 'transfer/ui/showimport.html.php';

jsVar('childrenDateLimit', $childrenDateLimit);
jsVar('parentTasks', $parentTasks);
jsVar('tasks', $oldTasks);
jsVar('jsDatas', array_values($datas));
jsVar('members', $teams);
jsVar('ignoreLang', $lang->project->ignore);
jsVar('overParentEstStartedLang', $lang->task->overParentEsStarted);
jsVar('overParentDeadlineLang', $lang->task->overParentDeadline);
jsVar('overChildEstStartedLang', $lang->task->overChildEstStarted);
jsVar('overChildDeadlineLang', $lang->task->overChildDeadline);
jsVar('taskDateLimit', $project->taskDateLimit);
jsVar('dropdownGroups', $dropdownGroups);

pageJS(<<<'JAVASCRIPT'
window.renderImportRowData = function($row, index, row)
{
    if(row.execution)
    {
        const modules = dropdownGroups['module'] == undefined      ? [] : (dropdownGroups['module'][row.execution] || []);
        const stories = dropdownGroups['story'] == undefined       ? [] : (dropdownGroups['story'][row.execution] || []);
        const users   = dropdownGroups['assignedTo'] == undefined  ? [] : (dropdownGroups['assignedTo'][row.execution] || []);

        $row.find('[data-name="module"]').find('[id^="module"]').on('inited', function(e, info)
        {
            let $modulePicker = info[0];
            $modulePicker.render({items: modules});
            $modulePicker.$.setValue(row.module);
        });
        $row.find('[data-name="story"]').find('[id^="story"]').on('inited', function(e, info)
        {
            let $storyPicker = info[0];
            $storyPicker.render({items: stories});
            $storyPicker.$.setValue(row.story);
        });
        $row.find('[data-name="assignedTo"]').find('[id^="assignedTo"]').on('inited', function(e, info)
        {
            let $assignedToPicker = info[0];
            $assignedToPicker.render({items: users});
            $assignedToPicker.$.setValue(row.assignedTo);
        });
    }

    if(row.id != undefined && tasks[row.id] != undefined)
    {
        const task = tasks[row.id];
        $row.attr('data-parent', task.parent).attr('data-id', task.id);
        if(parentTasks[task.parent] != undefined && taskDateLimit == 'limit')
        {
            const parentTask = parentTasks[task.parent];
            $row.find('[id^="estStarted"]').on('inited', function(e, info)
            {
                if(parentTask.estStarted == '') info[0].render({disabled: true});
            });
            $row.find('[id^="deadline"]').on('inited', function(e, info)
            {
                if(parentTask.deadline == '') info[0].render({disabled: true});
            });
        }
    }

    if(row.team != undefined)
    {
        let $estimate = $row.find('.form-batch-control[data-name="estimate"]');
        $estimate.empty();

        let teams = [];
        for(account of row.team) teams.push({'text': members[account], 'value': account});

        $.each(row.team, function(index, account)
        {
            $estimate.append('<div class="input-group estimate-box"></div>')
            let $estimateInputGroup = $estimate.find('.input-group').last();

            $estimateInputGroup.append($('<div class="form-group-wrapper picker-box"></div>').picker({name: 'team[' + row.id + '][]', items: teams, defaultValue: account, required: true}));;
            $estimateInputGroup.append($('<input type="text" name="estimate[' + row.id + '][]" id="estimate_' + index + '" value="' + row.estimate[index] + '" class="form-control" autocomplete="off">'));
        });
    }
};

$(document).off('change', 'input[name^="execution"]').on('change', 'input[name^="execution"]', function()
{
    const executionID       = $(this).val();
    const $currentRow       = $(this).closest('tr');
    const modules           = dropdownGroups['module'] == undefined ? [] : (dropdownGroups['module'][executionID] || []);
    const stories           = dropdownGroups['story'] == undefined  ? [] : (dropdownGroups['story'][executionID] || []);
    const users             = dropdownGroups['assignedTo'] == undefined  ? [] : (dropdownGroups['assignedTo'][executionID] || []);
    const $modulePicker     = $currentRow.find('[data-name="module"]').find('[id^="module"]').zui('picker');
    const $storyPicker      = $currentRow.find('[data-name="story"]').find('[id^="story"]').zui('picker');
    const $assignedToPicker = $currentRow.find('[data-name="assignedTo"]').find('[id^="assignedTo"]').zui('picker');

    $modulePicker.render({items: modules});
    $storyPicker.render({items: stories});
    $assignedToPicker.render({items: users});

    $modulePicker.$.setValue('0');
    $storyPicker.$.setValue('');
    $assignedToPicker.$.setValue('');
});

$(document).off('change', 'input[name^="estStarted"], input[name^="deadline"]').on('change', 'input[name^="estStarted"], input[name^="deadline"]', function()
{
    if(taskDateLimit != 'limit') return;

    const $currentRow = $(this).closest('tr');
    const taskID      = $currentRow.attr('data-id');
    if(taskID == undefined) return;

    const parentID    = tasks[taskID].parent;
    const field       = $(this).closest('.form-batch-control').data('name');
    const estStarted  = $currentRow.find('[name^=estStarted]').val();
    const deadline    = $currentRow.find('[name^=deadline]').val();
    const parentTask  = parentTasks[parentID] ? parentTasks[parentID] : {estStarted: '', deadline: ''};

    if(field == 'estStarted')
    {
        const $estStartedTd = $currentRow.find('td[data-name=estStarted]');
        $estStartedTd.find('.date-tip').remove();

        const $childrenEstStarted = $(this).closest('tbody').find('tr[data-parent="' + taskID + '"]').find('[name^=estStarted]');
        $childrenEstStarted.each(function(){$(this).zui('datePicker').render({disabled: estStarted.length == 0});});
        if(estStarted.length > 0)
        {
            let $datetip = $('<div class="date-tip"></div>');
            let parentEstStarted = typeof tasks[parentID] == 'undefined' || $(this).closest('tbody').find('[name="estStarted[' + parentID + ']"]').length == 0 ? parentTask.estStarted : $(this).closest('tbody').find('[name="estStarted[' + parentID + ']"]').val();

            if(parentEstStarted.length > 0 && estStarted < parentEstStarted) $datetip.append('<div class="form-tip text-danger">' + overParentEstStartedLang.replace('%s', parentEstStarted) + '</div>');

            let childEstStarted = childrenDateLimit[taskID] ? childrenDateLimit[taskID].estStarted : '';
            $childrenEstStarted.each(function()
            {
                if(childEstStarted.length == 0 || ($(this).val().length > 0 && $(this).val() < childEstStarted)) childEstStarted = $(this).val();
            });
            if(childEstStarted.length > 0 && estStarted > childEstStarted)
            {
                $datetip.append('<div class="form-tip text-warning">' + overChildEstStartedLang.replace('%s', childEstStarted) + '<span class="ignore-date ignore-child underline">' + ignoreLang + '</span></div>');
                $datetip.off('click', '.ignore-child').on('click', '.ignore-child', function(e){ignoreTip(e)});
            }
            $estStartedTd.append($datetip);
        }
    }

    if(field == 'deadline')
    {
        const $deadlineTd = $currentRow.find('td[data-name=deadline]');
        $deadlineTd.find('.date-tip').remove();

        const $childrenDeadline = $(this).closest('tbody').find('tr[data-parent="' + taskID + '"]').find('[name^=deadline]');
        $childrenDeadline.each(function(){$(this).zui('datePicker').render({disabled: deadline.length == 0});});
        if(deadline.length > 0)
        {
            let $datetip = $('<div class="date-tip"></div>');
            let parentDeadline = typeof tasks[parentID] == 'undefined' || $(this).closest('tbody').find('[name="deadline[' + parentID + ']"]').length == 0 ? parentTask.deadline : $(this).closest('tbody').find('[name="deadline[' + parentID + ']"]').val();

            if(parentDeadline.length > 0 && deadline > parentDeadline) $datetip.append('<div class="form-tip text-danger">' + overParentDeadlineLang.replace('%s', parentDeadline) + '</div>');

            let childDeadline = childrenDateLimit[taskID] ? childrenDateLimit[taskID].deadline : '';
            $childrenDeadline.each(function()
            {
                if(childDeadline.length == 0 || ($(this).val().length > 0 && $(this).val() > childDeadline)) childDeadline = $(this).val();
            });
            if(childDeadline.length > 0 && deadline < childDeadline)
            {
                $datetip.append('<div class="form-tip text-warning">' + overChildDeadlineLang.replace('%s', childDeadline) + '<span class="ignore-date ignore-child underline">' + ignoreLang + '</span></div>');
                $datetip.off('click', '.ignore-child').on('click', '.ignore-child', function(e){ignoreTip(e)});
            }
            $deadlineTd.append($datetip);
        }
    }
});

JAVASCRIPT
);
