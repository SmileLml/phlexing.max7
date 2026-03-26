/**
 * 标题列显示额外的内容。
 * Display extra content in the title column.
 *
 * @param  object result
 * @param  object info
 * @access public
 * @return object
 */
window.onRenderCell = function(result, {col, row})
{
    if(result && col.name == 'name')
    {
        const data  = row.data;
        const today = zui.formatDate(new Date(), 'yyyy-MM-dd');
        if(data.type == 'stage')
        {
            result.shift(); // 移除带链接的阶段名称
            let executionName  = `<span class='label secondary-pale flex-none'>${stageLang}</span> `;
            executionName     += data.name;
            executionName     += (!['done', 'closed', 'suspended'].includes(data.status) && today > data.end) ? `<span class="lab  el danger-pale ml-1 flex-none">${delayed}</span>` : '';
            result.push({html: executionName});
        }
        else
        {
            let taskPri = `<span class='mt-1 pri-${data.pri}'>${data.pri}</span> `;
            result.unshift({html: taskPri});

            if(typeof data.delay != 'undefined' && data.delay > 0)
            {
                result[result.length] = { html: '<span class="label danger-pale ml-1 flex-none nowrap">' + delayWarning.replace('%s', data.delay) + '</span>', className: 'flex items-end', style: { flexDirection: "column" } };
            }
        }
    }

    if(result && col.name == 'PM')
    {
        if(row.data.type == 'stage')
        {
            result.shift(); // 移除带链接的阶段名称
            result.push({html: '<span style="padding-left: 27px">' + row.data.PM + '</span>'});
        }
    }
    return result;
}
