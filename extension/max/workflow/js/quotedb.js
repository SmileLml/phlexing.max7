$(function()
{
    $('#subTableTree').tree('expand', $('#subTableTree > li').eq(0));

    $('#subTableTree > li > ul > li').mouseover(function()
    {
        $(this).find('a.preview').removeClass('hidden');
    }).mouseout(function()
    {
        $preview = $(this).find('a.preview');
        if(!$preview.hasClass('active')) $preview.addClass('hidden');
    });

    $.ajaxForm('#ajaxForm');
    $('#subTableTree .preview').click(function()
    {
        $('#subTableTree .preview').removeClass('active').addClass('hidden');
        $('#subTableTree label').removeClass('active');

        $(this).closest('li').find('label').addClass('active');
        $('#previewArea').load($(this).attr('data-url'))

        $(this).addClass('active').removeClass('hidden');
    });

    if(typeof(firstTableID) != 'undefined')
    {
        var $first = $('#subTableTree a.preview[data-id=' + firstTableID + ']');
        if($first.length) $first.click();
    }
})
