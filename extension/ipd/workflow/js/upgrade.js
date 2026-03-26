$(function()
{
    $('.upgradeBtn').click(function()
    {
        //var link = createLink('workflow', 'upgrade', 'module=' + window.moduleName + '&step=' + window.step + '&toVersion=' + $('#version').val() + '&mode=' + window.mode);
        var link = $(this).prop('href');
        if($('#version').val()) link += '&toVersion=' + $('#version').val();
        $('#triggerModal').load(link); 

        return false;
    });
})
