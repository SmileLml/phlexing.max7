<script>
$(function()
{
    $('#type').change(function()
    {
        var typeID = $(this).val();
        if(typeID)
        {
            var link = createLink('cmcl', 'ajaxGetTitle', 'typeID='+typeID);
            $.post(link, function(data)
            {
                $('#title').closest('tr').remove();
                $('#type').closest('tr').after(data);
                $('#title').chosen();
            })
        }
   });
   $('#type').change();
})
</script>
