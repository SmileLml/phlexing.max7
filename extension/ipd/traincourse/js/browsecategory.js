$(document).ready(function()
{
    /* Load the children of current category when page loaded. */
    var link = type == 'trainskill' ? createLink('traincourse', 'categoryChildren', 'type=' + type + '&categoryID=' + categoryID + '&originalType=') : createLink('traincourse', 'editCategory','type=' + type + '&categoryID=' + categoryID);
    $('#categoryBox').load(link);
    $('#treeMenuBox li:has(ul)').each(function()
    {
        $(this).children('.deleter').remove();
    });

    $('#treeMenuBox > .tree').tree();

    $.setAjaxLoader('#treeMenuBox .ajax, .panel-actions .ajax', '#categoryBox ','#childForm');
});
