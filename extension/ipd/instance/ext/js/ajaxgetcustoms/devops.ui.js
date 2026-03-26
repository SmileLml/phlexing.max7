window.setCustomField = function(obj)
{
    const changeField = typeof obj == 'string' ? obj : $(obj).attr('name');
    if(dependFields && dependFields[changeField])
    {
        dependFields[changeField].forEach((field) => {
            window.checkDepends(field);
            window.setCustomField(field);
        });
    }

    if(!$('#customFieldsTable').length) return;

    $('#customFieldsTable .custom-btn').addClass('disabled').attr('type', 'button');
    if(typeof customFields === 'undefined' || !customFields) return;

    for(let name in customFields)
    {
        if($(`#${name}`).closest('tr').hasClass('hidden')) continue;

        const value = getCustomValue(customFields[name].type, name, customFields[name].separator);
        if(value != customFields[name].value)
        {
            $('#customFieldsTable .custom-btn').removeClass('disabled').attr('type', 'submit');
            return;
        }
    }
};

window.getCustomValue = function(type, name, split = ',')
{
    if($(`.${name}-row-tr`).hasClass('hidden')) return '';

    let value = '';
    switch(type)
    {
        case 'radioList':
              value = $('input[name=' + name + ']:checked').val();
              break;
        case 'checkList':
              value = $('input[name^=' + name + ']:checked').map(function() { return $(this).val(); }).get();
              break;
        case 'picker':
              value = $('input[name=' + name + ']').val();
              break;
        case 'switcher':
              value = $('input[name=' + name + ']').is(':checked');
              break;
        default:
              value = $('input[name=' + name + ']').val();
              break;
    }

    if(Array.isArray(value)) value = value.join(split);
    return value;
};

window.checkDepends = function(fieldName)
{
    if(typeof customFields === 'undefined' || !customFields) return;

    if(typeof fieldName === 'object') fieldName = fieldName.name;

    const field = customFields[fieldName];
    if(!field || !field.depends || !field.depends.length) return;

    let canShow = true;
    field.depends.forEach(function(depend)
    {
        if(customFields[depend.key])
        {
            const dependField = customFields[depend.key];
            const value       = getCustomValue(dependField.type, dependField.name);

            if(depend.operator == 'eq' && value != depend.value) canShow = false;
            if(depend.operator == 'ne' && value == depend.value) canShow = false;
            if(depend.operator == 'lt' && value >= depend.value) canShow = false;
            if(depend.operator == 'gt' && value <= depend.value) canShow = false;
            if(depend.operator == 'in' && !value.split(',').includes(depend.value)) canShow = false;
        }
    });
    $(`.${field.name}-row-tr`).toggleClass('hidden', !canShow);
};

$(function()
{
    setTimeout(function()
    {
        if(typeof customFields === 'undefined' || !customFields) return;

        for(let name in customFields) window.setCustomField(name);
    }, 100);
});
