/**
 *  初始化交付物表单内容。
 *  Render deliverable form data.
 */
window.renderRowData = function($row, index, row)
{
    index = index + 1;
    $.each(['whenClosed'], function(i, value)
    {
        if(row)
        {
            $row.find('[data-name=' + value + ']').empty();
            if(value == 'whenClosed' || row.key == 'project_scrum' || row.key == 'product_scrum' || row.key == 'product_waterfall' || row.key == 'project_waterfall')
            {
                if(row[value])
                {
                    $.each(row[value], function(key, itemObject)
                    {
                        itemHTML = "<div class='item flex items-center pb-2'><div class='w-52' id='deliverable" + index + value + key + "'></div><label class='form-label' for='requiredBox'><div class='checkbox-primary'><input type='checkbox' id='required" + index + value + key + "' name='required[" + index + "][" + value + "][" + key + "]' " + (itemObject.required ? 'checked=checked' : '') + "><label for='required" + index + value + key + "'>" + requiredLabel + "</label></div></label><button type='button' class='form-batch-btn btn square ghost size-sm ml-1' data-on='click' data-call='addNode' data-params='event'><i class='icon icon-plus'></i></button><button type='button' class='form-batch-btn btn square ghost size-sm " + (row[value].length <= 1 ? 'opacity-0 cursor-default' : '') + "' data-type='remove' data-on='click' data-call='removeNode' data-params='event'><i class='icon icon-trash'></i></button></div>";
                        $row.find('[data-name=' + value + ']').append("<div class='deliverable col items-center' data-index='" + index + "' data-value='" + value + "' data-key='" + row[value].length + "' data-model='" + row.key + "'>" + itemHTML + '</div>');
                        new zui.Picker('#deliverable' + index + value + key, {items: [deliverables[itemObject.deliverable]], name: 'deliverable[' + index + ']['+ value +'][' + key + ']', defaultValue: itemObject.deliverable});
                    });
                }
                else
                {
                    itemHTML = '';
                    itemHTML += "<div class='item flex items-center pb-2'><div class='w-52' id='deliverable" + index + value + "0'></div><label class='form-label' for='requiredBox'><div class='checkbox-primary'><input type='checkbox' id='required" + index + value + "0' name='required[" + index + "][" + value + "][0]' checked=checked><label for='required" + index + value + "0'>" + requiredLabel + "</label></div></label><button type='button' class='form-batch-btn btn square ghost size-sm ml-1' data-on='click' data-call='addNode' data-params='event'><i class='icon icon-plus'></i></button><button type='button' class='form-batch-btn btn square ghost size-sm' data-type='remove' data-on='click' data-call='removeNode' data-params='event'><i class='icon icon-trash'></i></button></div>";
                    itemHTML += "<div class='item flex items-center pb-2'><div class='w-52' id='deliverable" + index + value + "1'></div><label class='form-label' for='requiredBox'><div class='checkbox-primary'><input type='checkbox' id='required" + index + value + "1' name='required[" + index + "][" + value + "][1]' checked=checked><label for='required" + index + value + "1'>" + requiredLabel + "</label></div></label><button type='button' class='form-batch-btn btn square ghost size-sm ml-1' data-on='click' data-call='addNode' data-params='event'><i class='icon icon-plus'></i></button><button type='button' class='form-batch-btn btn square ghost size-sm' data-type='remove' data-on='click' data-call='removeNode' data-params='event'><i class='icon icon-trash'></i></button></div>";

                    $row.find('[data-name=' + value + ']').append("<div class='deliverable col items-center' data-index='" + index + "' data-value='" + value + "' data-key='2' data-model='" + row.key + "'>" + itemHTML + '</div>');
                    new zui.Picker('#deliverable' + index + value + '0', {items: [], name: 'deliverable[' + index + ']['+ value +'][0]', defaultValue: ''});
                    new zui.Picker('#deliverable' + index + value + '1', {items: [], name: 'deliverable[' + index + ']['+ value +'][1]', defaultValue: ''});
                }
            }
            else
            {
                $row.find('[data-name=' + value + ']').append('<div>' + sprintTips + '</div>');
            }
        }
    });
}

/**
 *  获取交付物下拉列表。
 *  Get deliverable list.
 */
window.getDeliverables = function(event)
{
    const method   = $(event.target).closest('div.deliverable').data('value');
    const model    = $(event.target).closest('div.deliverable').data('model');
    const $picker  = $(event.target).zui('picker');
    const link     = $.createLink('workflowgroup', 'ajaxGetDeliverable', 'model=' + model + '&method=' + method + '&current=' + $picker.$.state.value);
    const formData = getFormData();
    $.post(link, formData, function(relations)
    {
        relations = JSON.parse(relations);
        $picker.render({items: relations});
    });
}

/**
 *  获取表单提交内容。
 *  Get Form data.
 */
window.getFormData = function()
{
    const form     = document.querySelector('form');
    const formData = {};
    for(let element of form.elements)
    {
        if(element.name) formData[element.name] = element.value;
    }

    return formData;
}

/**
 *  添加一行交付物节点。
 *  Add a node.
 */
window.addNode = function(event)
{
    const $row  = $(event.target).closest('td');
    const key   = $(event.target).closest('div.deliverable').data('key');
    const index = $(event.target).closest('div.deliverable').data('index');
    const value = $(event.target).closest('div.deliverable').data('value');

    html = "<div class='item flex items-center pb-2' data-index=" + index + " data-value=" + value + "><div class='w-52' id='deliverable" + index + value + key + "'></div><label class='form-label' for='requiredBox'><div class='checkbox-primary'><input type='checkbox' id='required" + index + value + key + "' name='required[" + index + "][" + value + "][" + key + "]' checked=checked><label for='required" + index + value + key + "'>" + requiredLabel + "</label></div></label><button type='button' class='form-batch-btn btn square ghost size-sm ml-1' data-on='click' data-call='addNode' data-params='event'><i class='icon icon-plus'></i></button><button type='button' class='form-batch-btn btn square ghost size-sm' data-type='remove' data-on='click' data-call='removeNode' data-params='event'><i class='icon icon-trash'></i></button></div>";
    $(event.target).closest('div').after(html);
    $(event.target).closest('div.deliverable').attr('data-key', key + 1);
    new zui.Picker('#deliverable' + index + value + key, {items: [], name: 'deliverable[' + index + '][' + value + '][' + key + ']', defaultValue: ''});
    checkBtn($row);
}

/**
 *  删除一行交付物节点。
 *  Remove a node.
 */
window.removeNode = function(event)
{
    if($(event.target).closest('button').hasClass('opacity-0')) return false;

    $row = $(event.target).closest('td');
    $(event.target).closest('div').remove();
    checkBtn($row);
}

/**
 *  检查删除交付物按钮是否可以点击。
 *  check delete btn.
 */
window.checkBtn = function(row)
{
    if($(row).find('div.flex').length >= 2)
    {
        $(row).find('.form-batch-btn[data-type=remove]').removeClass('opacity-0 cursor-default');
    }
    else
    {
        $(row).find('.form-batch-btn[data-type=remove]').addClass('opacity-0 cursor-default');
    }
}
