$(function()
{
    $('select[id*="source"]').change(function()
    {
        var source   = $(this).val();
        var attrID   = $(this).attr('id');
        var number   = attrID.substr(7);
        var objectID = $("#sourceNote_" + number).closest('td').attr('data-id');

        if(source == 'ditto') source = getLastSource(number)
        batchChangeSource(source, number, objectID);
    })
})
