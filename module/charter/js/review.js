$(function()
{
    if($('#check').prop("checked"))
    {
        $('tr[id="meetingBox"]').removeClass('hidden');
        $('#dataform #meetingBoxMinutes').val(1);
    }

    $('#check').on('change', function()
    {
        if($(this).is(':checked'))
        {
            $('tr[id="meetingBox"]').removeClass('hidden');
            $('#dataform #meetingBoxMinutes').val(1);
        }
        else
        {
            $('tr[id="meetingBox"]').addClass('hidden');
            $('#dataform #meetingBoxMinutes').val(0);
        }
    });
});
