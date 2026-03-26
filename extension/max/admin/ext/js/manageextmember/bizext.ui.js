$(function()
{
    $(document).on('change', 'input.check-all', function()
    {
        const checked = $(this).prop('checked');
        $(this).parents('.form-row').find('input[type=checkbox]').prop('checked', checked);
        window.showError();
    });

    $(document).on('change', 'input[name^=members]', function()
    {
        let   checked = true;
        const checkedCount = $(this).closest('.form-group').find('input[name^=members]:checked').length;
        const totalCount   = $(this).closest('.form-group').find('input[name^=members]').length;
        if(checkedCount < totalCount) checked = false;

        $(this).closest('.form-row').find('input.check-all').prop('checked', checked);
        window.showError();
    });
});

window.showError = function()
{
    const $form = $('#manageExtMember').find('form');
    const checkedCount = $form.find('input[name^=members]:checked').length;

    $form.find('button[type=submit]').removeAttr('disabled');
    if(checkedCount > licenseCount)
    {
        zui.Modal.alert(extGrantCountError.replace('%s', checkedCount).replace('%s', checkedCount - licenseCount));
        $form.find('button[type=submit]').attr('disabled', 'disabled');
    }
};
