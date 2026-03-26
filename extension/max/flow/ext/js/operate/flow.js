$(function()
{
    $('[data-provide="fileInputList"]').fileInputList();

    if(typeof window.action !== 'undefined')
    {
        if(window.action == 'approvalreview') $('#reviewResult.checkboxDIV').css('border', '0px');
    }
});
