/**
 * 获取文档详情侧边栏标签页定义。
 * Get the doc view sidebar tabs.
 *
 * @param {object} doc
 */
window._getDocViewSidebarTabs = window.getDocViewSidebarTabs; // Save the original method.
function getDocViewSidebarTabs(doc, info)
{
    tabList = window._getDocViewSidebarTabs(doc, info);
    if(info.mode == 'view' && doc.status != 'draft' && !doc.api)
    {
        tabList.push(
        {
            key   : 'relateObject',
            icon  : 'link',
            title : getLang('relateObject'),
            render: function(doc){return {fetcher: $.createLink('doc', 'ajaxGetRelatedObjects', `docID=${doc.id}`)}}
        });
    }
    return tabList;
}

/**
 * 重写文档应用的配置选项方法。
 * Override the method to set the doc app options.
 */
window._setDocAppOptions = window.setDocAppOptions; // Save the original method.
window.setDocAppOptions = function(_, options) // Override the method.
{
    options = window._setDocAppOptions(_, options);
    return $.extend(options,
    {
        getDocViewSidebarTabs: getDocViewSidebarTabs
    });
};
