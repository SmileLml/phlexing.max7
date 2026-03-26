<?php if($project->model == 'ipd' and !$planID):?>
<?php
js::set('minBeginDate', $project->begin);
js::set('maxEndDate', $project->end);
?>
<style>
#leftTopSection>h5,#leftTopSection>div{display: inline-block;}
</style>

<div class='pull-left btn-toolbar hidden' id='leftTopSection'>
    <h5><?php echo $lang->programplan->parallel;?> </h5>
    <div><?php echo html::radio('parallel', $lang->programplan->parallelList, $project->parallel, !$canParallel ? '' : 'disabled');?></div>
    <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->programplan->parallelTip;?>"></icon>
</div>

<script>
function updatePreDate(value, id)
{
    var currentIndex     = id.match(/\d+/g)[0];
    var elements         = $('#planForm [name*=end], #planForm [name*=begin]');
    var reversedElements = Array.from(elements).reverse();

    $(reversedElements).each(function(index, element) {
        var elementID    = $(element).attr('id');
        var elementIndex = elementID.match(/\d+/g)[0];
        var elementValue = $(element).val();

        if(currentIndex == elementIndex && id.indexOf('begin') !== -1 && elementID.indexOf('end') !== -1) return true;
        if(elementIndex > currentIndex || elementID == id) return true;

        $(element).datetimepicker('setEndDate', value);
        if(elementValue != '') return false;
    });
}

function updateAfterDate(value, id)
{
    var currentIndex     = id.match(/\d+/g)[0];
    var elements         = $('#planForm [name*=end], #planForm [name*=begin]');
    var reversedElements = Array.from(elements);

    $(reversedElements).each(function(index, element) {
        var elementID    = $(element).attr('id');
        var elementIndex = elementID.match(/\d+/g)[0];
        var elementValue = $(element).val();

        if(currentIndex == elementIndex && id.indexOf('end') !== -1 && elementID.indexOf('begin') !== -1) return true;
        if(elementIndex < currentIndex || elementID == id) return true;

        $(element).datetimepicker('setStartDate', value);
        if(elementValue != '') return false;
    });
}

$(function()
{
    $('.form-date').datetimepicker('setStartDate', minBeginDate);
    $('.form-date').datetimepicker('setEndDate', maxEndDate);

    $('.form-date').datetimepicker().on('changeDate', function(ev)
    {
        var parallel = $('#planForm input[name="parallel"]').val();
        if(parallel == '0')
        {
            var currentTarget = $(ev.target);
            var currentDate   = currentTarget.val();
            var currentID     = currentTarget.attr('id');
            updatePreDate(currentDate, currentID);
            updateAfterDate(currentDate, currentID);
        }
    });

    $('#planForm').append('<input type="hidden" name="parallel" value=<?php echo $project->parallel;?>>');
    $('#leftTopSection').appendTo('#mainContent .main-header').removeClass('hidden');

    $('input[name="parallel"]').on('click', function()
    {
        var parallelValue = $(this).val();
        $('#planForm input[name="parallel"]').val(parallelValue);
        if(parallelValue == 0) $('.form-date').val('');

        $('.form-date').datetimepicker('setStartDate', minBeginDate);
        $('.form-date').datetimepicker('setEndDate', maxEndDate);
    });
})
</script>
<?php endif;?>
