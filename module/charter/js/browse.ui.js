/**
 * 对部分列进行重定义。
 * Redefine the partial column.
 *
 * @param  array  result
 * @param  array  info
 * @access public
 * @return string|array
 */
window.renderCell = function(result, info)
{
    const charter = info.row.data;
    if(info.col.name == 'reviewStatusAB')
    {
        result[1].attrs['title'] = reviewStatusList[charter.reviewStatus] !== undefined ? reviewStatusList[charter.reviewStatus] : '';
        if(!charter.approval)
        {
            if(result[0] && result[0].props && result[0].props['href'])        delete result[0].props['href'];
            if(result[0] && result[0].props && result[0].props['data-toggle']) delete result[0].props['data-toggle'];
            result[0].type = 'span';
        }
    }
    if(info.col.name == 'level')
    {
        result[0].props['text'] = info.row.data.levelList[info.row.data.level];
    }
    if(info.col.name == 'actions' && info.row.data.prevCanceledStatus == 'wait')
    {
        result[0].props['items'].forEach((infoName) => {if(infoName.icon == 'magic') infoName.hint = activateHint});
    }

    return result;
}
