$(document).ready(function()
{
    $.setAjaxForm('#childForm');

    var initSortable = function()
    {
        $('#childList').sortable({trigger: '.sort-handle', selector: '.tree', dragCssClass: ''});
    }

    var setChildrenKey = function()
    {
        $('[value=new]').each(function()
        {
            maxID ++;
            $(this).parents('.tree').find('[id*=children]').attr('name', 'children[' + maxID + ']');
            $(this).attr('name', 'mode[' + maxID + ']');
        })
    }

    initSortable();
    setChildrenKey();
});
