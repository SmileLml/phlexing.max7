function changeOption(option)
{
    if($(option).val() == 'fixed')
    {
        $(option).removeClass('unit-col-7').addClass('unit-col-3');
        $(option).next().val('').removeClass('hidden').addClass('unit-col-4 border-left');
    }
    else
    {
        $(option).removeClass('unit-col-3').addClass('unit-col-7');
        $(option).next().val('').removeClass('unit-col-4').addClass('hidden');
    }
}

function addOptions(clickedButton)
{
    $(clickedButton).parent().parent().after(itemRow);
}

function delOptions(clickedButton)
{
    $(clickedButton).parent().parent().remove();
}
