window.submitForm = function(type)
{
    $('#importNoticeModal [name=insert]').val(type == 'insert' ? 1 : 0);
};

window.renderRowData = function($row, index, row)
{
    $row.find('[data-name="module"]').find('.picker-box').on('inited', function(e, info)
    {
        const $modulePicker = info[0];
        $modulePicker.render({items: modules[row.branch == undefined || row.branch == '' ? 0 : row.branch]});
        $modulePicker.$.setValue(row.module);
    });

    $row.find('[data-name="story"]').find('.picker-box').on('inited', function(e, info)
    {
        const $storyPicker = info[0];
        $storyPicker.render({items: stories[row.branch == undefined || row.branch == '' ? 0 : row.branch]});
        $storyPicker.$.setValue(row.story);
    });
}

function changeModule(event)
{
    const $target      = $(event.target);
    const moduleID     = $target.val();
    const $storyPicker = $target.closest('tr').find('.form-batch-control[data-name="story"] .picker').zui('picker');
    const oldStory     = $storyPicker.$.value;

    $storyPicker.render({items: stories[moduleID]});
    $storyPicker.$.setValue(oldStory);
}

function changeBranch(event)
{
    const $target       = $(event.target);
    const branchID      = $target.val();
    const $modulePicker = $target.closest('tr').find('.form-batch-control[data-name="module"] .picker').zui('picker');
    const oldModule     = $modulePicker.$.value;

    $modulePicker.render({items: modules[branchID]});
    $modulePicker.$.setValue(oldModule);
}

window.recomputeTimes = function()
{
    if(parseInt($('#maxImport').val())) $('#times').html(Math.ceil(parseInt($allCount) / parseInt($('#maxImport').val())));
};

window.setMaxImport = function()
{
    $.cookie.set('maxImport', $('#maxImport').val(), {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
};
