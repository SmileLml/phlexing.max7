$(document).off('click', '.import-btn').on('click', '.import-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return false;

    const url          = $('#importLdap').attr('action');
    const importDTable = $('#table-user-importldap').zui('dtable');
    const formData     = importDTable.$.getFormData();

    checkedList.forEach(function(id, index)
    {
        formData[`add[${id}]`] = id;

        let key        = id - 1 < 0 ? 0 : id - 1;
        let currentRow = dtable.$.layout.allRows[key];
        formData[`account[${id}]`]  = currentRow.data.account;
        formData[`realname[${id}]`] = currentRow.data.realname;
    });

    $.ajaxSubmit({url: url, data: formData});

    return false;
});
