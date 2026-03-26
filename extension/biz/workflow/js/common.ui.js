const toggleNavigator = () => {
    const navigator    = event.target.value;
    const $form        = $(event.target).closest('form');
    const $app         = $form.find('[name=app]');
    const module       = $form.find('[name=module]').val();
    const appPicker    = $app.zui('picker');
    const modulePicker = $form.find('[name=positionModule]').zui('picker');

    if(navigator == 'primary') {
        $app.closest('.form-group').addClass('hidden');

        appPicker.render({items: []});
        appPicker.$.setValue('');

        $.getJSON($.createLink('workflow', 'ajaxGetApps', 'splitProject=0'), function(apps) {
            modulePicker.render({items: apps});
            modulePicker.$.setValue('');
        });
    } else if(navigator == 'secondary') {
        $app.closest('.form-group').removeClass('hidden');

        $.getJSON($.createLink('workflow', 'ajaxGetApps', `splitProject=1&exclude=${module}`), function(apps) {
            appPicker.render({items: apps});
            appPicker.$.setValue('');
        });

        modulePicker.render({items: []});
        modulePicker.$.setValue('');
    } else {
        $app.closest('.form-group').addClass('hidden');

        appPicker.render({items: []});
        appPicker.$.setValue('');

        modulePicker.render({items: []});
        modulePicker.$.setValue('');
    }
}

const toggleApp = () => {
    const app    = event.target.value;
    const picker = $(event.target).closest('form').find('[name=positionModule]').zui('picker');

    if(app) {
        $.getJSON($.createLink('workflow', 'ajaxGetAppMenus', `app=${app}`), function(menus) {
            picker.render({items: menus});
            picker.$.setValue('');
        });
    } else {
        picker.render({items: []});
        picker.$.setValue('');
    }
};

const toggleApproval = () => {
    const approval = event.target.value;
    $(event.target).closest('form').find('#approvalFlow').closest('.form-group').toggleClass('hidden', approval == 'disabled');
};

window.loadDropdownMenu = function(e)
{
    const $form = $(e.target).closest('form');
    const app   = $form.find('[name=app]').val();

    const $position      = $form.find('[name=position]');
    const positionPicker = $position.zui('picker');
    const positionItems  = positionPicker.options.items;

    let items = [];
    for(i in positionList)
    {
        if(e.target.value == 'my' && i == 'before') continue;
        items.push({'text': positionList[i], 'value': i, 'key': i});
    }

    if(items.length != positionItems.length)
    {
        positionPicker.render({items: items});
        positionPicker.$.setValue($position.val());
    }

    $form.find('#dropMenus').empty();
    $.getJSON($.createLink('workflow', 'ajaxGetDropMenus', `app=${app}&menu=${e.target.value}`), function(menus)
    {
        if(menus.length == 0) return;
        $form.find('#dropMenus').append("<div class='dropPicker' style='width: 120px'></div>");
        new zui.Picker('#dropMenus .dropPicker', {items: menus, name: 'dropMenu'});
    });
};
