$(document).ready(function()
{
    $('#type').change(function(val)
    {
        var type = $('#type').val();
        if(type.indexOf('Report') !== -1)
        {
            $('#dataset').closest('tr').hide();
        }
        else
        {
            $('#dataset').closest('tr').show();
        }
    })
});

