function showName()
{
    var lang = $(event.target).val();
    $('.nameinputs').addClass('hidden');
    $('.nameinputs[data-lang=' + lang + ']').removeClass('hidden');
}

function showDesc()
{
    var lang = $(event.target).val();
    $('.descinputs').addClass('hidden');
    $('.descinputs[data-lang=' + lang + ']').removeClass('hidden');
}
