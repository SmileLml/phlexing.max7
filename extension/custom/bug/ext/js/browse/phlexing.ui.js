window.onRenderCell = function(result, {row, col})
{
    if(from == 'doc') return result;

    if(result && col.name == 'title')
    {
        result[0].props.className = 'overflow-hidden';
        if(row.data.color) result[0].props.style = 'color: ' + row.data.color;
        const module = this.options.modules[row.data.module];
        if(module) result.unshift({html: '<span class="label gray-pale rounded-full whitespace-nowrap w-auto">' + module + '</span>'}); // 添加模块标签

        if(parseInt(row.data.case))
        {
            caseLink = $.createLink('testcase', 'view', "caseID=" + row.data.case + "&version=" + row.data.caseVersion);
            result.push({html: '<a href="' + caseLink + '"class="text-gray" title="' + row.data.case + '">[' + caseCommonLang + '#' + row.data.case + ']</a>'});
        }
    }

    if(result[0] && col.name == 'deadline')
    {
        const bug = row.data;
        if(['resolved', 'closed'].includes(bug.status)) return result;

        const yesterday = zui.formatDate(zui.createDate() - 24 * 60 * 60 * 1000, 'yyyy-MM-dd');
        if(result[0] <= yesterday) result[0] = {html: '<span class="label danger-pale rounded-full size-sm">' + result[0] + '</span>'};
    }

    if(col.name == 'source')
    {
        if(!row.data.source) return result;
        var sourceHtml = '<div class="dtable-name-flex">';
        sourceHtml += '<div><a href="' + $.createLink('bug', 'view', 'bugID=' + row.data.source) + '" target="_blank">' + result[0] + '</a></div>';
        sourceHtml += '</div>';

        result[0] = {html: sourceHtml};
    }

    return result;
}
