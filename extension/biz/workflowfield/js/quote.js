$(function()
{
    $('#fieldTree').tree('expand', $('#fieldTree > li').eq(0));

    $('#fieldTree > li > ul > li').mouseover(function()
    {
        $(this).find('a.preview').removeClass('hidden');
    }).mouseout(function()
    {
        $preview = $(this).find('a.preview');
        if(!$preview.hasClass('active')) $preview.addClass('hidden');
    });

    $.ajaxForm('#ajaxForm');
    $('#fieldTree .preview').click(function()
    {
        $('#fieldTree .preview').removeClass('active').addClass('hidden');
        $('#fieldTree label').removeClass('active');

        $(this).closest('li').find('label').addClass('active');
        $('#previewArea').load($(this).attr('data-url'));

        $(this).addClass('active').removeClass('hidden');
    });

    if(typeof(firstFieldID) != 'undefined')
    {
        var $first = $('#fieldTree a.preview[data-id=' + firstFieldID + ']');
        if($first.length) $first.click();
    }
})
