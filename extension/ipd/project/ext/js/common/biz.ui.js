window.changeCharter = function()
{
    if(model == 'ipd') $('#categoryHide').remove();

    const charterID      = $('[name=charter]').val();
    const categoryPicker = model == 'ipd' ? $("[name='category']").zui('picker') : {};
    if(charterID > 0)
    {
        const link = $.createLink('charter', 'ajaxGetCharterInfo', 'id=' + charterID);
        $.get(link, function(data)
        {
            data = JSON.parse(data);
            if(!$('[name=name]').val()) $('[name=name]').val(data.name);

            if(model == 'ipd')
            {
                categoryPicker.$.setValue(data.category);
                categoryPicker.render({disabled: true});
                $('[name=category]').after("<input type='hidden' name='category' id='categoryHide' value='" + data.category + "'/>");
            }

            $('[name=budget]').val(data.budget);
            $('[name=budgetUnit]').val(data.budgetUnit);
            if(model == 'ipd') toggleHasProduct(data.category);
        })
    }
    else
    {
        if(typeof currentMethod !== 'undefined' && currentMethod != 'edit')
        {
            $('[name=name]').val('');
            $('[name=budget]').val('');
            $('[name=budgetUnit]').val('CNY');
        }

        if(model == 'ipd')
        {
            categoryPicker.$.setValue('IPD');
            categoryPicker.render({disabled: false});
        }
    }

    changeType();
}
