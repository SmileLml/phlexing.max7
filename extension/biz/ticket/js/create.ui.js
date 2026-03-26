$(function()
{
    if(productID == 'all') $('#manageModule').addClass('hidden');
})

window.addNewLine = function(e)
{
    const obj     = e.target;
    const newLine = $(obj).closest('.customerBox').clone();

    /* 将已有组件的最大name属性的值加1赋值给新行. */
    let index = 0;
    $(".customerBox [name^='customer']").each(function()
    {
        let id = $(this).attr('name').replace(/[^\d]/g, '');
        id = parseInt(id);
        id ++;
        index = id > index ? id : index;
    });

    /* 重新初始化新一行的下拉控件. */
    newLine.find('.form-control.customerInput').val('').attr('name', 'customer[' + index + ']').attr('id', 'customer[' + index + ']');
    newLine.find('.form-control.contactInput').val('').attr('name', 'contact[' + index + ']').attr('id', 'contact[' + index + ']');
    newLine.find('.form-control.notifyEmailInput').val('').attr('name', 'notifyEmail[' + index + ']').attr('id', 'notifyEmail[' + index + ']');

    newLine.addClass('pt-4');
    newLine.find('.removeLine').removeClass('hidden');
    $(obj).closest('.customerBox').after(newLine.zuiInit());
}

window.removeLine = function(e)
{
    $(e.target).closest('.customerBox').remove();
}

window.loadAll = function()
{
    const productID = $('#ticketCreateForm [name=product]').val();
    loadModules(productID);
    loadBuilds(productID);
}

function loadModules(productID)
{
    const moduleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=ticket&branch=all&rootModuleID=0&returnType=items');
    const moduleID   = $('#ticketCreateForm [name=module]').val();
    $.getJSON(moduleLink, function(modules)
    {
        const $modulePicker = $('#ticketCreateForm [name=module]').zui('picker');
        $modulePicker.render({items: modules});
        $modulePicker.$.setValue(moduleID);

        const hidenModule = (modules.length != 1) || (!isAdmin && !authedProducts.includes(productID));
        $('#moduleBox #manageModule').toggleClass('hidden', hidenModule);
        if(modules.length == 1) $('#moduleBox #manageModule').attr('href', $.createLink('tree', 'browse', `rootID=${productID}&viewType=ticket`));
    })
}

function loadBuilds(productID)
{
    const openedBuild = $('#ticketCreateForm [name^=openedBuild]').val() ? $('#ticketCreateForm [name^=openedBuild]').val().toString() : 0;
    const buildLink = $.createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuilds&build=' + openedBuild);
    $.getJSON(buildLink, function(data)
    {
        const $buildPicker = $('#ticketCreateForm [name^=openedBuild]').zui('picker');
        $buildPicker.render({items: data});
        $buildPicker.$.setValue(openedBuild);
    })
}

renderModulePicker = function(rootID, viewType)
{
    if(config.debug) console.log('[ZIN] Rendering module picker');

    const link   = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + rootID + '&viewtype=' + viewType + '&branch=&rootModuleID=0&returnType=items');
    $.getJSON(link, function(data)
    {
        $('#moduleBox [name=module]').zui('picker').render({items: data});
        $('#moduleBox #manageModule').addClass('hidden');
    });
}
