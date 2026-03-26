window.renderCell = function(result, info)
{
    if(info.col.name == 'name' && result)
    {
        const group = info.row.data;
        let html = '';
        if(group.main == '1') html += "<span class='label gray-pale rounded-xl'>" + buildinLang + "</span>";
        if(html) result.push({html});
    }
    return result;
};
