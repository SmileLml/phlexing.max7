$(function()
{
    $("div.fileBox:has(ul.files-list)").find(".file-input-list").hide();

    $(document).on('click', '[onclick*=deleteFile]', function() {
        $(this).parents('div.isdeleted').remove();
        $(this).parents('div.fileBox').find('.file-input-list').show();
        $(this).parents('div.fileBox').find('.fileType').hide();
    });
})
