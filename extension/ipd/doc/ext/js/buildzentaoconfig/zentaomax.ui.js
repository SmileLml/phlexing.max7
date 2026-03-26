window.clickSubmit = function()
{
    const docID = getDocApp()?.docID;
    $("#buildZentaoConfig form #templateID").val(docID);
}

/**
 * 根据选择的项目更新产品组件为项目关联的产品。
 * Update product items by project.
 *
 * @param  object $e
 * @access public
 * @return void
 */
window.loadProduct = function(e)
{
    const projectIdList = $(e.target).val();
    const link = $.createLink('doc', 'ajaxGetLinkedProducts', 'project=' + projectIdList);
    $.get(link, function(data)
    {
        data = JSON.parse(data);
        const $productPicker = $("[name^='product']").zui('picker');
        $productPicker.render({items: data});
        $productPicker.$.setValue('');
    });
}
