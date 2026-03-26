function computeIndex(event)
{
    let $impact = '';
    let $chance = '';
    let $ratio  = '';
    let $pri    = '';
    if(config.currentMethod == 'batchcreate' || config.currentMethod == 'batchedit')
    {
        $row    = $(event.target).closest('tr');
        $impact = $row.find('input[name^=impact]');
        $chance = $row.find('input[name^=chance]');
        $ratio  = $row.find('input[name^=ratio]');
        $pri    = $row.find('input[name^=pri]');
    }
    else
    {
        $impact = $('input[name=impact]');
        $chance = $('input[name=chance]');
        $ratio  = $('input[name=ratio]');
        $pri    = $('input[name=pri]');
    }

    let impact = $impact.val();
    let chance = $chance.val();
    let rate   = parseInt(impact * chance);
    let pri    = '';

    if(0 <= rate && rate <= 5)   pri = 'low';
    if(5 < rate && rate <= 12)   pri = 'middle';
    if(15 <= rate && rate <= 25) pri = 'high';

    $ratio.val(rate);
    $pri.zui('priPicker').$.setValue(pri);
}
