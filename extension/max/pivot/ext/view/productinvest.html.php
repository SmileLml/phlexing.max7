<style>
#mainContent > .side-col.col-lg{width: 235px}
.hide-sidebar #sidebar{width: 0 !important}
.c-count {width: 150px;}
.c-name {width: 100px;}
#conditions {display: flex;}
#conditions .condition-options {margin-left: 16px;}
.pl-4 {padding-left: 1rem;}
</style>
<div class='flex bg-canvas p-2' id='conditions'>
  <div class='input-group w-1/4 pl-4'>
    <span class='input-group-addon'><?php echo $lang->pivot->otherLang->product;?></span>
    <?php echo html::select('product', array('' => '') + $products, $productID, "class='form-control chosen' onchange='loadProductInvest()'");?>
  </div>
  <div class='input-group w-1/4 pl-4'>
    <span class='input-group-addon'><?php echo $lang->pivot->otherLang->productStatus;?></span>
    <?php echo html::select('productStatus', $statusList, $productStatus, "class='form-control chosen' onchange='loadProductInvest()'");?>
  </div>
  <div class='input-group w-1/4 pl-4'>
    <span class='input-group-addon'><?php echo $lang->pivot->otherLang->productType;?></span>
    <?php echo html::select('productType', $typeList, $productType, "class='form-control chosen' onchange='loadProductInvest()'");?>
  </div>
</div>
<?php if(empty($investData)):?>
<div class="cell bg-canvas">
  <div class="dtable-empty-tip">
    <p><span class="text-muted"><?php echo $lang->error->noData;?></span></p>
  </div>
</div>
<?php else:?>
<div class='cell'>
  <div class='panel rounded ring-0 bg-canvas'>
    <div class='panel-heading'>
      <div class='panel-title'><?php echo $title;?></div>
    </div>
    <div class='panel-body pt-0'>
      <div data-ride='table'>
        <table class='table table-condensed table-striped table-bordered table-fixed no-margin' id='productList'>
          <thead>
            <tr class='text-center text-left bg-canvas'>
              <th class='c-name text-left border'><?php echo $lang->product->name;?></th>
              <th class='c-count border'><?php echo $lang->pivot->projects;?></th>
              <th class="c-count border"><?php echo $lang->pivot->storyConsumed;?></th>
              <th class="c-count border"><?php echo $lang->pivot->taskConsumed;?></th>
              <th class="c-count border"><?php echo $lang->pivot->bugConsumed;?></th>
              <th class="c-count border"><?php echo $lang->pivot->caseConsumed;?></th>
              <th class="c-count border"><?php echo $lang->pivot->totalConsumed;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($investData as $invest):?>
            <tr class="text-center">
              <td class='border'><div class='text-ellipsis text-left' title='<?php echo $invest->name;?>'><?php echo $invest->name?></div></td>
              <td class='border'><?php echo $invest->projectCount;?></td>
              <td class='border'><?php echo $invest->storyConsumed;?></td>
              <td class='border'><?php echo $invest->taskConsumed;?></td>
              <td class='border'><?php echo $invest->bugConsumed;?></td>
              <td class='border'><?php echo $invest->caseConsumed;?></td>
              <td class='border'><?php echo $invest->totalConsumed;?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
<script>
function loadProductInvest()
{
    var conditions = '';
    /*
    $('#conditions input[type=checkbox]').each(function(i)
    {
        if($(this).prop('checked')) conditions += $(this).val() + ',';
    })
    conditions = conditions.substring(0, conditions.length - 1);
    */

    var productID     = $('#product').val();
    var productStatus = $('#productStatus').val();
    var productType   = $('#productType').val();

    var params = window.btoa('conditions=' + conditions + '&productID=' + productID + '&productStatus=' + productStatus + '&productType=' + productType);
    var link = $.createLink('pivot', 'preview', 'dimension=' + <?php echo $dimensionID;?> + '&group=' + <?php echo $groupID;?> + '&method=productinvest&params=' + params);
    loadPage(link, '#pivotContent');
}
</script>
