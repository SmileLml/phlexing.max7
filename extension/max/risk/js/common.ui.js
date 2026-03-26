function computeIndex()
{
    let impact      = $('input[name=impact]').zui('picker').$.value;
    let probability = $('input[name=probability]').zui('picker').$.value;
    let rate        = parseInt(impact * probability);
    let pri         = '';

    if(0 <= rate && rate <= 5)   pri = 'low';
    if(5 < rate && rate <= 12)   pri = 'middle';
    if(15 <= rate && rate <= 25) pri = 'high';

    $('input[name=rate]').val(rate);
    $('input[name=pri]').zui('priPicker').$.setValue(pri);
}
