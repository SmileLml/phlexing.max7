window.addItem = function(event)
{
    const obj     = $(event.target);
    const newLine = $('.product-box').eq(0).clone();

    let chosenProducts = [];
    let options        = zui.Picker.query("[name^='products']").options;
    options = JSON.parse(JSON.stringify(options));

    /* 处理新一行控件的显示/隐藏，宽度/是否居中等样式问题. */
    newLine.addClass('newLine');
    newLine.find('.form-label').remove();

    /* 重新初始化新一行的下拉控件. */
    const index = $('.product-box').length;
    newLine.find('.form-group .linkProduct').replaceWith(`<div id=products${index} class='form-group-wrapper picker-box'></div>`);
    newLine.find('.releases').replaceWith(`<div id=release${index} class='form-group-wrapper picker-box'></div>`);
    newLine.find('.form-group .c-action').removeClass('first-action');

    obj.closest('.product-box').after(newLine);

    options.name         = `products[${index}]`;
    options.defaultValue = '';
    options.disabled     = false;
    options.placeholder  = '';
    console.log(options);
    new zui.Picker(`#products${index}`, options);
    new zui.Picker(`#release${index}`, {name: `release[${index}]`, items: []});
}

window.removeItem = function(event)
{
    const obj = $(event.target);
    if(obj.closest('.product-box .c-action').hasClass('first-action')) return false;
    obj.closest('.product-box').remove();
}

window.loadRelease = function(e)
{
    const productID     = $(e.target).val();
    const formGroup     = $(e.target).closest('.form-group');
    const releasePicker = formGroup.find('[name^=release]').zui('Picker');
    releasePicker.$.clear();
    if(productID == 0)
    {
        releasePicker.render({items: []});
        return;
    }

    const link = $.createLink('release', 'ajaxGetByProduct', "product=" + productID);
    $.getJSON(link, function(data)
    {
        releasePicker.render({items: data});
    });
}

$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();

    checkedList.forEach((id) => {
        const data = dtable.$.getRowInfo(id).data;
        form.append('idList[]', data.id);
    });

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data:form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});
