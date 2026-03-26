function setComment(type)
{
    $('#commentBox').toggle();
    $('#commentBox #type').val(type);
    $('#commentBox textarea').val('');
    window.editor['comment'].html('');
    $('.ke-container').css('width', '100%');
}

function like(feedbackID)
{
    var likeLink = createLink('feedback', 'ajaxLike', 'feedbackID=' + feedbackID);
    $('.likesBox').load(likeLink);
}

$(function()
{
    if(window['browseType'] == 'bysearch') $.toggleQueryBox(true);

    $(':checkbox[id^=isFeedback]').click(function()
    {
        var checked = $(this).prop('checked');
        $(this).closest('tr').find('input[type=checkbox]').prop('checked', checked);
    })

    $('#product').change(function()
    {
        var productID = $(this).val();
        var module    = typeof(moduleID) != 'undefined' ? moduleID : '';
        var link      = createLink('feedback', 'ajaxGetModule','productID=' + productID);
        $.post(link, function(data)
        {
            $('#module').replaceWith(data);
            $('#module_chosen').remove();
            $('#module').val(module).chosen();
            $('#module').change();
        })
    });

    /* Init pri. */
    $('#pri').on('change', function()
    {
        var $select   = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });

    if(typeof(productID) != 'undefined' && productID && productID !== 'all') $('#product').change();
})
