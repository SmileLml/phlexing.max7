$(function()
{
    var $showdata = $('#showdata');
    $showdata.load($showdata.data('url') + ' #dataWrapper', function()
    {
        if($.fn.datatable)
        {
            $('#datatable').datatable({fixedLeftWidth: 200, scrollPos: 'out', customizable: false, sortable: false, mergeRows: true, fixedHeader:true, ready:function()
            {
                $('div.table-footer').css('width', $('#datatable-datatable').width());
                fixScroll();
            }
            });
            $('#dataWrapper .pager').pager();
        }
    });
});

/* Fix bug #20413. */
$(window).resize(function()
{
    $('div.table-footer').css('width', $('#datatable-datatable').width());
    $('div.datatable-rows .fixed-left tr').each(function(index)
    {
        var leftHeight  = $(this)[0].getBoundingClientRect().height;
        var rightHeight = $('div.datatable-rows .flexarea tr').eq(index)[0].getBoundingClientRect().height;

        if(leftHeight < rightHeight) $(this).height(rightHeight);
        if(leftHeight > rightHeight) $('div.datatable-rows .flexarea tr').eq(index).height(leftHeight);
    });
})
