/**
 * 对部分列进行重定义。
 * Redefine the partial column.
 *
 * @param  array  result
 * @param  array  info
 * @access public
 * @return string|array
 */
window.renderCell = function(result, {col, row})
{
    if(col.name == 'actions' && row.data.key == 1)
    {
        if(result[0].length == 0) return result;
        if(result[0].props.items.length == 1)
        {
            if(result[0]['props']['items'][0]['className'].includes('delete-relation-btn')) result[0]['props']['items'][0]['disabled'] = true;
        }
        if(result[0].props.items.length == 2)
        {
            result[0]['props']['items'][1]['disabled'] = true;
        }
    }

    return result;
}
