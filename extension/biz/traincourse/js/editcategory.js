$(document).ready(function()
{
    $.setAjaxForm('#editForm', function()
    {
        /* After the form posted, refresh the treeMenuBox content. */
        source = createLink('traincourse', 'browseCategory', 'type=trainskill&categoryID' + categoryID + '=0&root=0') + ' #treeMenuBox';
        $('#treeMenuBox').parent().load(source, function()
        {
            /* Rebuild the category menu after treeMenuBox refreshed. */
            $(".tree").tree('expand');
        });
    });

    $('.group-item label.checkbox').css('float', 'left').css('margin-right', '10px').css('width', '100px');

    $('.chosen').chosen();
});
