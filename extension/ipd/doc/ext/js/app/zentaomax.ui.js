const docListFunction = window.docAppActions['doc-list'];
$.extend(window.docAppActions,
{
    'doc-list': function(info)
    {
        let actions  = docListFunction.call(this, info);
        let dropdown = actions ? actions.find(action => action && action.type === 'dropdown') : null;
        if(canModifySpace() && hasPriv('create') && dropdown) dropdown.items.splice(1, 0, {icon: 'file-archive', text: getLang('createByTemplate'), command: 'selectTemplate'});
        return actions;
    }
})

$.extend(window.docAppCommands,
{
    selectTemplate: function(_, args)
    {
        const docApp = getDocApp();
        if(!docApp.libList.length) return zui.Modal.alert(getLang('createLibFirst'));

        const url = $.createLink('doc', 'selectTemplate');
        zui.Modal.open({size: 'lg', url:  url, position: 'fit'});
    },
    openCreateDocModal: function(_, args)
    {
        setTimeout(() =>
        {
            const docApp = getDocApp();
            docApp.executeCommand('startCreateDoc', [0, args?.[0] ?? 0]);
        }, 500);
    }
});
