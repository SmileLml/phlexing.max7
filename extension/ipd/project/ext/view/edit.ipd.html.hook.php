<?php
if($project->model == 'kanban') return;
js::set('currentCharter', $project->charter);
$this->loadModel('roadmap');
$charters = array(0 => '') + $this->loadModel('charter')->getPairs();

$charter  = '<tr>';
$charter .= "<th class='w-120px'>";
$charter .= $lang->project->charter;
$charter .= '</th>';
$charter .= '<td>';
$charter .= html::select('charter', $charters, $project->charter, "class='form-control chosen' $disableModel");
$charter .= '</td>';
$charter .= '</tr>';

$category  = '<tr>';
$category .= '<th>';
$category .= $lang->project->category;
$category .= '</th>';
$category .= '<td>';
$category .= html::select('category', $lang->project->categoryList, $project->category, "class='form-control chosen' disabled='disabled'");
$category .= '</td>';
$category .= "<td id='hasProductBox' class='hidden'>";
$category .= html::radio('hasProduct', $lang->project->projectTypeList, $project->hasProduct);
$category .= '</td>';
$category .= '</tr>';

$productRoadmap  = '<tr id="productRoadmap">';
$productRoadmap .= '<th>';
$productRoadmap .= $lang->project->manageProductRoadmap;
$productRoadmap .= '</th>';
$productRoadmap .= '<td>';
$productRoadmap .= '<div class="input-group">';
$productRoadmap .= '<span class="input-group-addon">' . $lang->product->common . '</span>';
$productRoadmap .= html::input('productName', '', "class='form-control' disabled");
$productRoadmap .= html::hidden('products[]');
$productRoadmap .= html::hidden('branch[0][]', 0);
$productRoadmap .= '</div>';
$productRoadmap .= '</td>';
$productRoadmap .= '<td>';
$productRoadmap .= '<div class="input-group">';
$productRoadmap .= '<span class="input-group-addon">' . $lang->roadmap->common . '</span>';
$productRoadmap .= html::input('roadmapName', '', "class='form-control' disabled");
$productRoadmap .= '</div>';
$productRoadmap .= '</td>';
$productRoadmap .= '</tr>';
?>

<script>
$(function()
{
    $('#dataform > .table').prepend(<?php echo json_encode($charter);?>);
    $('#PM').closest('tr').before(<?php echo json_encode($category);?>);

    $('#charter').chosen();
    $('#category').chosen();
    $('#projectType').closest('tr').remove();
    $('#model').closest('tr').remove();
    $('input[name="multiple"').closest('tr').remove();

    $('#category').change(function()
    {
        var category = $(this).val();
        toggleHasProduct(category);
    })

    $('#charter').change(function()
    {
        var charterID = $('#charter').val();
        $('tr.newLine').remove();
        toggleProductGroup(charterID);
    })
})

function toggleHasProduct(category)
{
    hasCharter = $('#charter').val();
    if(category == 'CPD' && hasCharter == '0')
    {
        $('#hasProductBox').removeClass('hidden');
    }
    else
    {
        $('#hasProductBox').find("input[name='hasProduct'][value='1']").prop('checked', true);
        $('#hasProductBox').addClass('hidden');
        $('#productTitle, #linkPlan').closest('tr').show();
    }
}

function toggleProductGroup(charterID)
{
    $('#categoryHide').remove();
    $('#productRoadmap').remove();

    if(charterID != '0')
    {
        $('.productsBox').closest('tr').addClass('hidden');
        $('.productsBox').closest('tr').after(<?php echo json_encode($productRoadmap);?>);

        var link = createLink('charter', 'ajaxGetCharterInfo', 'id=' + charterID);
        $.get(link, function(data)
        {
            data = JSON.parse(data);
            $('#category').val(data.category).attr('disabled', true).trigger("chosen:updated").chosen();
            $('#category').after("<input type='hidden' name='category' id='categoryHide' value='" + data.category + "'/>");
            $('#budget').val(data.budget);
            $('#budgetUnit').val(data.budgetUnit);
            $('#productRoadmap #productName').val(data.productName);
            $('#roadmapName').val(data.roadmapName);
            $('#products').val(data.product);
            toggleHasProduct(data.category);
        })
    }
    else
    {
        $('.productsBox').closest('tr').removeClass('hidden');
        $('#category').attr('disabled', false).trigger("chosen:updated").chosen();
    }
}

toggleProductGroup(currentCharter);
</script>
