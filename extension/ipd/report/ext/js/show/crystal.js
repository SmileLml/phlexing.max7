function handleDatatableReady()
{
    $('th.datatable-head-cell').each(function() { $(this).attr('title', $(this).html()); });

    var rowspan    = 1;
    var compareVal = '';
    var mergeIndex = 0;
    $('.datatable-rows .datatable-rows-span:first table tr').each(function()
    {
        var leftHeight = $(this).height();
        var dataIndex = $(this).data('index');

        var $right = $('.datatable-rows .datatable-rows-span:last table tr[data-index=' + dataIndex + ']');
        var rightHeight = $right.height();

        var trHeight = leftHeight;
        if(leftHeight <= rightHeight) trHeight = rightHeight;
        $right.height(trHeight);
        $(this).height(trHeight);

        if(dataIndex == 0)
        {
            compareVal = $(this).find('td:first').html();
            mergeIndex = dataIndex;
        }

        if(mergeIndex != dataIndex)
        {
            if(compareVal == $(this).find('td:first').html())
            {
                rowspan += 1;
                $(this).parent().find('tr').eq(mergeIndex).find('td:first').attr('rowspan', rowspan);
                $(this).find('td:first').remove();
            }
            else
            {
                rowspan    = 1;
                compareVal = $(this).find('td:first').html();
                mergeIndex = dataIndex;
            }
        }
    });
}

$(function()
{
    handleDatatableReady();
    fixScroll();
});

function fixScroll()
{
    var $scrollwrapper = $('div.datatable').first().find('.scroll-wrapper:first');
    if($scrollwrapper.size() == 0)return;

    var $tfoot       = $('div.datatable').first().find('table tfoot:last');
    var scrollOffset = $scrollwrapper.offset().top + $scrollwrapper.find('.scroll-slide').height();
    if($tfoot.size() > 0) scrollOffset += $tfoot.height();
    if($('div.datatable.head-fixed').size() == 0) scrollOffset -= '29';
    var windowH = $(window).height();
    var bottom  = $tfoot.hasClass('fixedTfootAction') ? 14 + $tfoot.height() : 14;
    if(typeof(ssoRedirect) != "undefined") bottom = 14;
    if(scrollOffset > windowH + $(window).scrollTop()) $scrollwrapper.css({'position': 'fixed', 'bottom': '0px'});
    $('.datatable .scroll-slide.scroll-pos-out').css('bottom', '0px');
    $(window).scroll(function()
    {
          newBottom = $tfoot.hasClass('fixedTfootAction') ? 14 + $tfoot.height() : 14;
          if(typeof(ssoRedirect) != "undefined") newBottom = 14;
          if(scrollOffset <= windowH + $(window).scrollTop())
          {
            $scrollwrapper.css({'position':'relative', 'bottom': '0px'});
            $('.datatable .scroll-slide.scroll-pos-out').css('bottom', '-14px');
          }
          else if($scrollwrapper.css('position') != 'fixed' || bottom != newBottom)
          {
            $scrollwrapper.css({'position': 'fixed', 'bottom': newBottom + 'px'});
            bottom = newBottom;
            $('.datatable .scroll-slide.scroll-pos-out').css('bottom', '-' + newBottom + 'px');
          }
    });
}
