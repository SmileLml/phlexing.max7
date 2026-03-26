window.confirmDelete = function(dimensionID)
{
    var link = $.createLink('dimension', 'ajaxCheckBIContent', "dimensionID=" + dimensionID);
    $.get(link, function(data)
    {
        if(data)
        {
            zui.Modal.alert(canNotDelete);
        }
        else
        {
            zui.Modal.confirm(confirmDelete).then(function(result)
            {
                if(!result) return;
                var deleteUrl = $.createLink('dimension', 'delete', "dimensionID=" + dimensionID);
                $.ajaxSubmit({url: deleteUrl});
            });
        }
    })
}
