/**
 * 根据状态变更操作按钮的语言项。
 * Change recall btn lang of object status.
 *
 * @param  object result
 * @param  object info
 * @access public
 * @return object
 */
window.onRenderCell = function(result, {row, col})
{
    if(col.name == 'actions')
    {
        for(index in row.data.actions)
        {
            if(row.data.actions[index].name == 'recall') row.data.actions[index].hint = (row.data.status == 'changing' ? recallChange : recall);
        }
    }
    if(col.name == 'title' && result)
    {
        if(row.data.color) result[0].props.style = 'color: ' + row.data.color;

        if(row.data.parent > 0)
        {
            let html = "<span class='label gray-pale rounded-xl clip'>" + childrenAB + "</span> ";
            result.unshift({html});
        }
    }
    return result;
}
