window.loadProductRoadmaps = function(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;

    let roadmapLink   = $.createLink('demand', 'ajaxGetRoadmaps', 'productID=' + productID + '&branch=' + branch);
    let $roadmapIdBox = $('#roadmapIdBox');

    $.get(roadmapLink, function(data)
    {
        let items = JSON.parse(data);
        let $inputGroup = $roadmapIdBox.closest('.input-group');
        $inputGroup.html("<div id='roadmapIdBox' class='w-full'><div class='picker-box' id='roadmap'></div></div>")
        new zui.Picker('#roadmapIdBox #roadmap', {items: items, name: 'roadmap', defaultValue: ''});
        if(items.length == 0)
        {
            $inputGroup.append('<a class="btn btn-default" type="button" data-toggle="modal" data-type="iframe" data-size="lg" href="' + $.createLink({moduleName: 'roadmap', methodName: 'create', vars: 'productID=' + productID + '&branch=' + branch, params: {onlybody: 'yes'}}) + '"><i class="icon icon-plus"></i></a>');
            $inputGroup.append('<button class="refresh btn" type="button" onclick="window.loadProductRoadmaps(' + productID + ')"><i class="icon icon-refresh"></i></button>');
        }
    })
};

window.loadBranchRoadmaps = function()
{
    var branch    = $('[name=branch]').val();
    var productID = $('[name=product]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    window.loadProductRoadmaps(productID, branch);
    window.loadBranchModule(productID);
}
