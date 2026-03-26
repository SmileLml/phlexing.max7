$(function()
{
    if(type != 'query') return;

    var dataviewSql   = JSON.parse(sessionStorage.getItem('dataviewSql'));
    var fieldSettings = sessionStorage.getItem('fieldSettings');
    var langs         = sessionStorage.getItem('langs');
    $.each(dataviewSql, function(index, value)
    {
        if(value.name == 'sql')
        {
            dataviewSql = value.value;
            return;
        }
    });

    $('#sql').val(dataviewSql);
    $('#fields').val(fieldSettings);
    $('#langs').val(langs);
});
