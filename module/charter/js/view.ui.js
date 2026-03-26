window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'name' && !userViewProjects.includes(',' + row.data.id + ',')) result[0] = {html: "<div>" + row.data.name + "</div>"};

    return result;
}
