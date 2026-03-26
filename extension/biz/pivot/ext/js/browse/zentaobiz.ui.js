window.onRenderCell = function(result, {col, row})
{
    const {name} = col;
    const {data} = row;
    const draftLabel = `<span class='label secondary-pale'>${draftIcon}</span>`;
    const labelClass = 'w-full flex items-center justify-end';

    if(name == 'name')
    {
        if(data.type == 'table')
        {
            result[0] = data.name;
            return result;
        }
        if(data.stage != 'published') return [...result, {html: draftLabel, className: labelClass}];
    }
    if(name == 'version')
    {
        if(data.type == 'table') return [];
        const disabled = data.stage == 'draft' ? 'disabled title="' + disableVersionTip + '"' : 'data-hover="' + viewVersion + '" data-version="#' + data.version + '" data-pivot="' + row.id + '" data-size="lg"';
        return [{html: `<button class="btn ghost size-sm btn-version text-primary-600 ${data.stage == 'draft' ? 'disabled' : ''}" ${disabled}>#${data.version}</button>`}];
    }
    return result;
}

$(function()
{
    $(document).on('mouseover', '.btn-version', function()
    {
        if($(this).hasClass('disabled')) return;
        $(this).removeClass('ghost').addClass('rounded-full').text($(this).data('hover'));
    });
    $(document).on('mouseout', '.btn-version', function()
    {
        if($(this).hasClass('disabled')) return;
        $(this).addClass('ghost').removeClass('rounded-full').text($(this).data('version'));
    });
});

$(document).off('click','.btn-version').on('click', '.btn-version', function(e)
{
    const version = $(e.target).attr('data-version').replace(/#/g, '');;
    const pivotID = $(e.target).attr('data-pivot');
    loadModal($.createLink('pivot', 'versions', `groupID=${groupID}&pivotID=${pivotID}&version=${version}`), null, {size: 'lg'});
})
