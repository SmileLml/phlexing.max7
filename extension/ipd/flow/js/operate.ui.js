window.renderReviewerItem = function(result, info)
{
    if(info.col.name == 'approval_node') result.push({html: "<input type='hidden' name='approval_id[]' value='" + info.row.data.approval_id + "' />"});

    if(info.col.name == 'approval_reviewer')
    {
        if(info.row.data.types.indexOf('reviewer') !== -1)
        {
            if(info.row.data.reviewers) result.push({html: '<div class="otherReview">' + info.row.data.reviewers + '</div>'}, {className: 'order-last'});
        }
        else
        {
            if(info.row.data.reviewers) result.push({html: info.row.data.reviewers})
        }
    }

    if(info.col.name == 'approval_ccer')
    {
        if(info.row.data.types.indexOf('ccer') !== -1)
        {
            if(info.row.data.ccers) result.push({html:'<div class="otherCcer">' + info.row.data.ccers + '</div>'}, {className: 'order-last'});
        }
        else
        {
            if(info.row.data.ccers) result.push({html: info.row.data.ccers});
        }
    }

    info.row.id = info.row.data.approval_id;

    return result;
}

window.getReviewerCellProps = function(cell)
{
    const hasReviewer = cell.row.data.types.indexOf('reviewer') !== -1;

    return {items: cell.row.data.reviewerItems, multiple: true, className: 'mx-2' + (hasReviewer ? '' : ' hidden'), name: `approval_reviewer[${cell.row.index}]`};
}

window.getCcerCellProps = function(cell)
{
    const hasCcer = cell.row.data.types.indexOf('ccer') !== -1;

    return {items: cell.row.data.ccerItems, multiple: true, className: 'mx-2' + (hasCcer ? '' : ' hidden'), name: `approval_ccer[${cell.row.index}]`};
}
