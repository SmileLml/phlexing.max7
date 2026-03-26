window.deleteGrade = function()
{
    $(this).closest('tr').remove();
    $('#gradeList tbody').find('tr').last().find('.btn-add-grade').removeClass('hidden');

    /* compute grade. */
    let index = 1;
    $('#gradeList tbody').find('.gradeTr').each(function()
    {
        $(this).find('td.index').text(index);
        $(this).find('input[type=hidden]').val(index);
        index ++;
    });
}

window.addGrade = function()
{
    let newRow   = $(this).closest('tr').clone();
    let maxIndex = $(this).closest('tbody').find('tr').length;
    newIndex = parseInt(maxIndex);

    newRow.find('input').val('');
    newRow.find('.btn-delete-grade').attr('href', 'javascript:void').removeClass('ajax-submit hidden').on('click', deleteGrade);
    newRow.find('.btn-close, .btn-active').remove();
    newRow.find("input[type=hidden]").val(newIndex);
    newRow.find('td.index').text(newIndex);
    newRow.find('td.grade-status').text(enableLang);
    $(this).closest('tbody').append(newRow);

    $(this).addClass('hidden');
};
