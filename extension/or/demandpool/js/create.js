$('#group').change(function()
{
    var group = $(this).val();
    var link  = createLink('opinion', 'ajaxGetOwner', 'group=' + group);

    $.post(link, function(data)
    {
        $('#owner').val(data);
        $('#owner').trigger('chosen:updated');
    })
})
