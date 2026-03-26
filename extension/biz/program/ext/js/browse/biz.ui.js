window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        if(typeof result[0].props == 'object') result[0].props.className = 'clip w-max';
        if(row.data.postponed) result[result.length] = {html:'<span class="label size-sm circle danger-pale w-max">' + row.data.delayInfo + '</span>'};
        if(row.data.type == 'program' && !isAdmin && !privPrograms.includes(row.data.id.toString())) result[0].type = 'span';
        return result;
    }

    if(col.name === 'budget')
    {
        let budgetHtml = `<div>${row.data.budget}</div>`;
        if(typeof(row.data.exceedBudget) != 'undefined')
        {
            let iconSign = ' <span class="icon icon-exclamation text-danger"></span>';
            let menu     = '<menu class="dropdown-menu custom">';
            let dropMenu = menu;
            dropMenu    += '<div class="mb-1"><span class="text-gray">' + projectBudgetLang + ': </span><span class="font-bold">' + row.data.rawBudget + '</span></div>';
            dropMenu    += '<div class="mb-1"><span class="text-gray">' + remainingBudgetLang + ': </span><span class="font-bold">' + row.data.remainingBudget + '</span></div>';
            dropMenu    += '<div class="text-danger">' + exceededBudgetLang + ': <span class="font-bold">' + row.data.exceedBudget + '</span></div>';

            if(row.data.type == 'program')
            {
                if(row.data.parent == 0) iconSign = ' <span class="icon icon-exclamation-sign text-danger"></span>';
                dropMenu  = menu;
                dropMenu += '<div class="mb-1"><span class="text-gray">' + programBudgetLang + ': </span><span class="font-bold">' + row.data.rawBudget + '</span></div>';
                dropMenu += '<div class="mb-1"><span class="text-gray">' + sumSubBudgetLang + ': </span><span class="font-bold">' + row.data.subBudget + '</span></div>';
                dropMenu += '<div class="text-danger">' + exceededBudgetLang + ': <span class="font-bold">' + row.data.exceedBudget + '</span></div>';
            }
            iconSign   = '<span data-toggle="dropdown" data-trigger="hover" data-placement="right-start">' + iconSign + '</span>';
            budgetHtml = `<div>${row.data.budget}${iconSign}${dropMenu}</div>`
        }
        result[0] = {html: budgetHtml, className:'flex w-full items-end mr-1', style:{flexDirection:"column"}};
        return result;
    }

    if(col.name === 'invested')
    {
        result[0] = {html: '<div>' + row.data.invested + ' <small class="text-gray">' + langManDay + '</small></div>', className:'flex w-full items-end', style:{flexDirection:"column"}};
        return result;
    }

    if(col.name == 'charter')
    {
        const charterName = charterList[row.data.charter] !== undefined ? charterList[row.data.charter] : '';
        const charterHtml = hasCharterViewPriv ? '<a href="' + $.createLink('charter', 'view', "charterID=" + row.data.charter) + '">' + charterName + '</a>' : charterName;
        result[0] = {html: '<div>' + charterHtml + '</div>', className:'flex w-full', style:{flexDirection:"column"}};
    }

    return result;
}
