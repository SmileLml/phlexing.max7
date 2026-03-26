$(function()
{
    $('select[id*="source"]').change(function()
    {
        var source = $(this).val();
        var attrID = $(this).attr('id');
        var number = attrID.substr(7);

        if(source == 'ditto') source = getLastSource(number)
        batchChangeSource(source, number);
    })
})
