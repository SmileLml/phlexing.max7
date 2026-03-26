window.onRenderRow = function(row, rowIdx, data)
{
    if(typeof(data) != 'undefined') row.children('[data-name=ACTIONS]').find('button[data-type="delete"]').remove();
}
