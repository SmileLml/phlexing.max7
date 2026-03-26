/**
 * 点击模板卡片。
 * Click template card.
 *
 * @param  object event
 * @access public
 * @return void
 */
window.clickCard = function(event)
{
    const templateCard = event.target.closest('.templateCard');
    if(!templateCard) return;

    $(templateCard).addClass('border-primary checkedTemplate');
    $('.nextButton button').removeClass('disabled');
    if($(templateCard).find('.cornerMark').length === 0)
    {
        $(templateCard).append('<div class="cornerMark"><i class="icon icon-check checkIcon"></i></div>');
    }

    const selectTemplateID = $(templateCard).data('templateID');
    const templateCards    = $('.templateCardPanel').find('div.templateCard');
    $(templateCards).each(function()
    {
        let templateID = $(this).data('templateID');
        if(templateID !== selectTemplateID)
        {
            $(this).removeClass('border-primary');
            $(this).removeClass('checkedTemplate');
            $(this).find('.cornerMark').remove();
        }
    });
}

/**
 * 处理搜索框事件。
 * Handle search box change.
 *
 * @param  string value
 * @access public
 * @return void
 */
window.handleSearchBoxChange = function(value)
{
    /* 过滤value，仅保留字母、数字和汉字。*/
    /* Filter value, only keep letters, numbers and Chinese characters.*/
    value = value.replace(/[^\w\u4e00-\u9fa5]/g, '');
    const scopeID = $('#featureBar').find('.nav-item a.active').data('id');
    const url     = $.createLink('doc', 'selectTemplate', `scopeID=${scopeID}&searchName=${value}`);
    loadModal(url);
}

/**
 * 点击下一步按钮事件。
 * Click next button event.
 *
 * @access public
 * @return void
 */
window.clickNextButton = function()
{
    const selectTemplateID = $('.checkedTemplate').data('templateID');
    if(!selectTemplateID) return;

    const formData = new FormData();
    formData.append('templateID', selectTemplateID);
    $.ajaxSubmit({url: $.createLink('doc', 'selectTemplate'), data: formData});
}
