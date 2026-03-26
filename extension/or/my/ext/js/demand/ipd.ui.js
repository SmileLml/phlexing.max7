/**
 * 对标题列进行重定义。
 * Redefine the title column.
 *
 * @param  array  result
 * @param  array  info
 * @access public
 * @return string|array
 */
window.renderCell = function(result, info)
{
    if(info.col.name == 'actions')
    {
        for(index in info.row.data.actions)
        {
            if(info.row.data.actions[index].name == 'recall') info.row.data.actions[index].hint = (info.row.data.status == 'changing' ? recallChange : recall);
        }
    }
    if(info.col.name == 'title' && result[0])
    {
        const demand = info.row.data;
        if(demand.parent > 0)
        {
            let html = "<span class='label gray-pale rounded-xl' title='" + children + "'>" + childrenAB + "</span>";
            result.unshift({html});
        }
    }
    return result;
}
