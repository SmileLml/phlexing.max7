<?php include $app->getModuleRoot() . 'transfer/view/showimport.html.php';?>
<script>

function changeModule(index, productID, moduleID)
{
    var link = createLink('ticket', 'ajaxGetModule', 'productID=' + productID + '&isChosen=0' + '&number=' + index + '&moduleID=' + moduleID + '&field=module');
    $.get(link, function(data)
    {
        $('#module' + index).next('.picker').remove();
        $('#module' + index).replaceWith(data);
        $('#module' + index).picker({chosenMode: true});
        $('#module' + index).attr('isInit', true);
        $('#module' + index).attr('name', 'module[' + index + ']');
    })
}

$('#showData').on('change', '.picker-select', function(e)
{
    var id        = $(this).attr('id');
    var field     = $(this).attr('data-field');
    var moduleID  = $(this).val();
    var index     = Number(id.replace(/[^\d]/g, " "));
    var productID = $('#product' + index).val() == '' ? 0 : $('#product' + index).val();

    if(field === 'product') changeModule(index, productID, moduleID);
});

$('#showData').on('mouseover', '.picker', function(e)
{
    var myPicker = $(this);
    var field    = myPicker.prev().attr('data-field');
    if(field == 'module')
    {
        var name     = myPicker.prev().attr('name');
        var value    = myPicker.prev('select').val();
        if(typeof(name) == 'undefined') return false;
        var index    = Number(name.replace(/[^\d]/g, " "));
        var productID = $('#product' + index).val() == '' ? 0 : $('#product' + index).val();
        
        changeModule(index, productID, value)
    }
});
</script>