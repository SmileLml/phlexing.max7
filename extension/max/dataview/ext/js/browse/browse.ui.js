$(function()
{
    setTimeout(function(){$("a[data-toggle='tab'][href='#" + type + "']")[0].click()}, 300);
})

function renderTabpane(event)
{
    var currentTab = $(event.target).find('.active');
    const tabName  = currentTab.attr('href');
    if(tabName !== undefined)
    {
        const tree = $(tabName + ' >  #moduleMenu > .tree').zui();
        if(tree !== undefined)
        {
            const newItems = JSON.parse(tabName == '#view' ? groupTree : originTable);
            tree.render({items: newItems, show: false});
        }
    }
}
