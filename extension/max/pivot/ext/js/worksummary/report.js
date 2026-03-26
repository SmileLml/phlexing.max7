function getParams()
{
    var begin = $('#conditions').find('#begin').val();
    var end   = $('#conditions').find('#end').val();
    var dept  = $('#conditions').find('#dept').val();
    if(begin.indexOf('-') != -1)
    {
        var beginarray = begin.split("-");
        var begin = '';
        for(i=0 ; i < beginarray.length ; i++)
        {
            begin = begin + beginarray[i];
        }
    }
    if(end.indexOf('-') != -1)
    {
        var endarray = end.split("-");
        var end = '';
        for(i=0 ; i < endarray.length ; i++)
        {
            end = end + endarray[i];
        }
    }

    var page       = $('#mainContent').find('.main-col').find('.table-footer').find('.current-page').data('page');
    var recPerPage = $('#mainContent').find('.main-col').find('.table-footer').find('.current-recperpage').data('recperpage');

    return [begin,end,dept,page,recPerPage];
}

function locationPage(params)
{
    var [begin,end,dept,page,recPerPage] = params;
    var paramsBtoa = window.btoa('begin=' + begin + '&end=' + end + '&dept=' + dept + '&recTotal=0&recPerPage=' + recPerPage + '&pageID=' + page);
    var link = createLink('pivot', 'preview', 'dimension=' + dimension + '&group=' + groupID + '&module=pivot&method=worksummary&params=' + paramsBtoa);
    location.href = link;
}

function changeParams()
{
    locationPage(getParams());
}

function workSummaryPager(obj)
{
    var [begin,end,dept,page,recPerPage] = getParams();

    $obj = $(obj);
    if($obj.hasClass('recPerPage'))
    {
        page = 1;
        recPerPage = $obj.children().first().data('size');
    }
    else if($obj.hasClass('first-page') || $obj.hasClass('left-page') || $obj.hasClass('right-page') || $obj.hasClass('last-page'))
    {
        page = $obj.children().first().data('page');
    }

    locationPage([begin,end,dept,page,recPerPage]);
}
