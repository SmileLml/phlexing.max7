changeType = function()
{
    const type  = event.target.value;
    const $form = $(event.target).closest('form');
    $form.find('[name^=options]').closest('.form-group').toggleClass('hidden', type != 'option');
    $form.find('#sql').closest('.form-group').toggleClass('hidden', type != 'sql');
    $form.find('[name=keyField]').closest('.form-group').toggleClass('hidden', type != 'sql');
    $form.find('[name=module]').closest('.form-group').toggleClass('hidden', type != 'system');
    $form.find('[name=lang]').closest('.form-group').toggleClass('hidden', type != 'lang');
};

changeSql = function()
{
    const $sql = $(event.target);
    if($sql.val() == '') return false;

    $.post($.createLink('workflowdatasource', 'ajaxCheckSql'), {sql: $sql.val()}, function(response)
    {
        const $keyPicker   = $('[name=keyField]').zui('picker');
        const $valuePicker = $('[name=valueField]').zui('picker');

        $keyPicker.$.setValue('');
        $valuePicker.$.setValue('');

        response = JSON.parse(response);
        if(response.result == 'success') {
            $keyPicker.render({items: response.options});
            $valuePicker.render({items: response.options});
        }
        if(response.result == 'fail') {
            $keyPicker.render({items: []});
            $valuePicker.render({items: []});

            const $tip = $('<div class="form-tip ajax-form-tip text-danger pre-line" id="sqlTip"><div>').text(response.message);
            $sql.addClass('has-error').after($tip);
        }
    });
};

changeModule = function()
{
    const module = event.target.value;
    if(module == '') return false;

    const $form = $(event.target).closest('form');
    const $methodPicker = $form.find('[name=method]').zui('picker');
    const $methodDesc   = $form.find('#methodDesc');

    $methodPicker.$.setValue('');
    $methodPicker.render({items: []});
    $methodDesc.val('');
    $form.find('#paramsDIV').empty();

    $.getJSON($.createLink('workflowdatasource', 'ajaxGetModuleMethods', 'module=' + module), function(methods)
    {
        $methodPicker.render({items: methods});
    });
};

changeMethod = function()
{
    const $form  = $(event.target).closest('form');
    const module = $form.find('[name=module]').val();
    const method = event.target.value;
    if(module == '' || method == '') return false;

    $.get($.createLink('workflowdatasource', 'ajaxGetMethodComment', 'module=' + module + '&method=' + method), function(methodDesc)
    {
        $form.find('#methodDesc').val(methodDesc).attr('title', methodDesc);
    });

    var link = $.createLink('workflowdatasource', 'ajaxGetMethodParams', 'module=' + module + '&method=' + method);
    $('#paramsDIV').load(link);
};

addOption = function(target)
{
    const $inputGroup = $(event.target).closest('.input-group');
    $inputGroup.after($inputGroup.clone());
    $inputGroup.next().find('input').val('');
};

delOption = function(target)
{
    const $inputGroup = $(event.target).closest('.input-group');
    if($inputGroup.siblings('.input-group').length > 0) {
        $inputGroup.remove();
    } else {
        $inputGroup.find('input').val('');
    }
};

onRenderCell = function(result, {row, col})
{
    if(result) {
        if(col.name == 'datasource') {
            if(row.data.type == 'option' && result[0]) return [Object.values(JSON.parse(result[0])).join()];
            if(row.data.type == 'category') return ['treeModel->getOptionMenu(0, "datasource_' + row.data.id + '")'];
            if(row.data.type == 'system' && result[0]) {
                const datasource = JSON.parse(result[0]);
                const params = datasource.params.map(function(param) {return param.name + ' = "' + param.value + '"'});
                return [datasource.module + 'Model->' + datasource.method + '(' + params.join(', ') + ')'];
            }
        }
        if(col.name == 'buildin') return [{html: row.data.buildin == 1 ? '<i class=\"icon icon-check text-success\"></i>' : '<i class=\"icon icon-close text-danger\"></i>'}];
    }
    return result;
}
