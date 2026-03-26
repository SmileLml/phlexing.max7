var defaultProject   = $('#project').prop("outerHTML");
var defaultExecution = $('#execution').prop("outerHTML");

$(function()
{
    var $showdata = $('#showdata');
    $showdata.load($showdata.data('url') + ' #dataWrapper', function()
    {
        if($.fn.datatable)
        {
            $showdata.find('table#datatable').datatable({fixedLeftWidth: 200, scrollPos: 'out', customizable: false, sortable: false, mergeRows: true, fixedHeader:true,
                ready:function()
                {
                    $('div.table-footer').css('width', $('#datatable-datatable').width());
                    fixScroll();
                    setTimeout(function()
                    {
                        $('.bar').css('left', '0px');
                        $('.datatable-wrapper .table-datatable').css('left', '0px');
                        $('.iframe').modalTrigger();
                    }, 100);
                }
            });
        }

        if(parseInt($('#product').val()) > 0)
        {
            $('#product').trigger('change');
        }
        else if($('#project') && parseInt($('#project').val()) > 0)
        {
            $('#project').trigger('change');
        }
    });

    var dateVal = $('#featurebar ul.nav li #date').val();
    $('#date').focus(function(){$(this).val('').datetimepicker('update');}).blur(function(){$(this).val(dateVal)});

    $('#userBox').change(function()
    {
        if($('#user').val())
        {
            $('#showUser').val('all');
            $('#showUser').trigger("chosen:updated");
            $('#showUser_chosen').css('pointer-events', 'none')
            $('#showUser_chosen').children('.chosen-single').addClass('grey');
        }
        else
        {
            $('#showUser_chosen').css('pointer-events', 'initial');
            $('#showUser_chosen').children('.chosen-single').removeClass('grey');
        }
    });
    $(".sidebar-toggle").click(function()
    {
        $(window).scroll();
        $('.datatable-head, div.table-footer').css('width', $('#datatable-datatable').width());
        $('.datatable-wrapper .table-datatable').css('left', 0);
        var barLeft = $('.scroll-wrapper .bar').offset().left;
        var slideWidth = $('.scroll-wrapper .scroll-slide').width();
        $('.scroll-wrapper .bar').css('left', barLeft > slideWidth ? slideWidth : barLeft);
    });
});

$(window).resize(function()
{
    $('.datatable-head, div.table-footer').css('width', $('#datatable-datatable').width());
    $('div.datatable-rows .fixed-left tr').each(function(index)
    {
        var leftHeight  = $(this)[0].getBoundingClientRect().height;
        var rightHeight = $('div.datatable-rows .flexarea tr').eq(index)[0].getBoundingClientRect().height;

        if(leftHeight < rightHeight) $(this).height(rightHeight);
        if(leftHeight > rightHeight) $('div.datatable-rows .flexarea tr').eq(index).height(leftHeight);
    });
})
