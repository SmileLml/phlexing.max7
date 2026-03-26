<?php
if($model == 'kanban') return;
$charter  = '<tr>';
$charter .= "<th class='w-120px'>";
$charter .= $lang->project->charter;
$charter .= '</th>';
$charter .= '<td>';
$charter .= html::select('charter', $charters, $copyedCharter, "class='form-control chosen'");
$charter .= '</td>';
$charter .= '</tr>';

$category  = '<tr>';
$category .= '<th>';
$category .= $lang->project->category;
$category .= '</th>';
$category .= '<td>';
$category .= html::select('category', $lang->project->categoryList, '', "class='form-control chosen'");
$category .= '</td>';
$category .= "<td id='hasProductBox' class='hidden'>";
$category .= html::radio('hasProduct', $lang->project->projectTypeList, 1);
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
$productRoadmap .= html::hidden('products[0]', 0, "class='roadmapProduct'");
$productRoadmap .= html::hidden('branch[0][]', 0);
$productRoadmap .= '</div>';
$productRoadmap .= '</td>';
$productRoadmap .= '<td>';
$productRoadmap .= '<div class="input-group">';
$productRoadmap .= '<span class="input-group-addon">' . $lang->roadmap->common . '</span>';
$productRoadmap .= html::input('roadmapName', '', "class='form-control' disabled");
$productRoadmap .= '</div>';
$productRoadmap .= '</td>';
if($model != 'kanban')
{
    $linkStoryHtml  = '<td>';
    $linkStoryHtml .= html::checkbox('isLinkStory', array('checked' => $lang->project->linkStoryToProject), 'checked');
    $linkStoryHtml .= '</td>';
    $productRoadmap .= $linkStoryHtml;
}
$productRoadmap .= '</tr>';
?>

<script>
$(function()
{
    $('#dataform > .table').prepend(<?php echo json_encode($charter);?>);
    $('#charter').chosen();

    /* If is IPD peoject replace projectType. */
    if(model == 'ipd')
    {
        $('#PM').closest('tr').before(<?php echo json_encode($category);?>);
        $('#category').chosen();
        $('#projectType').closest('tr').remove();

        $('#category').change(function()
        {
            var category = $(this).val();
            toggleHasProduct(category);
        })
    }

    $('#charter').change(function()
    {
        var charterID = $('#charter').val();
        $('#categoryHide').remove();
        $('#productRoadmap').remove();
        $('tr.newLine').remove();

        if(charterID != '0' && charterID != null)
        {
            $('.productsBox').closest('tr').addClass('hidden');
            $('.productsBox').closest('tr').after(<?php echo json_encode($productRoadmap);?>);

            var link = createLink('charter', 'ajaxGetCharterInfo', 'id=' + charterID);
            $.get(link, function(data)
            {
                data = JSON.parse(data);
                if(!$('#name').val()) $('#name').val(data.name);
                $('#category').val(data.category).attr('disabled', true).trigger("chosen:updated").chosen();
                $('#category').after("<input type='hidden' name='category' id='categoryHide' value='" + data.category + "'/>");
                $('#budget').val(data.budget);
                $('#budgetUnit').val(data.budgetUnit);
                $('#productRoadmap #productName').val(data.productName);
                $('#roadmapName').val(data.roadmapName);
                $('.roadmapProduct').val(data.product);
                toggleHasProduct(data.category);
            })
        }
        else
        {
            $('.productsBox').closest('tr').removeClass('hidden');
            $('#name').val('');
            $('#budget').val('');
            $('#budgetUnit').val('CNY');
            $('#category').val('IPD').attr('disabled', false).trigger("chosen:updated").chosen();
        }
    })

    if($('#charter').val() != '0') $('#charter').trigger('change');
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
</script>
