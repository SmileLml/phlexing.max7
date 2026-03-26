function addClassify(clickedButton)
{
    $(clickedButton).parent().parent().after(itemRow);
}

function delClassify(clickedButton)
{
    var trLength = $('tbody').children('.text-center').length;
    if(trLength > 4) {$(clickedButton).parent().parent().remove()};
}