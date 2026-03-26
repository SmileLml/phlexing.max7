<style>
#mainContent > .side-col.col-lg{width: 235px}
.hide-sidebar #sidebar{width: 0 !important}
</style>
<div class='flex bg-canvas p-2 gap-2' id='conditions'>
  <div class='input-group w-1/4'>
    <span class='input-group-addon'><?php echo $lang->pivot->product;?></span>
    <?php echo html::select('product', $products, $productID, 'onchange="selectProduct(this.value);" class="form-control chosen"')?>
  </div>
  <div class='input-group w-1/4'>
    <span class='input-group-addon'><?php echo $lang->pivot->moduleName;?></span>
    <?php echo html::select('module' , $modules,  $moduleID,  'onchange="selectModule(this.value);"  class="form-control chosen"')?>
  </div>
</div>
<?php if(empty($stories)):?>
<div class="cell bg-canvas">
  <div class="dtable-empty-tip">
    <p><span class="text-muted"><?php echo $lang->error->noData;?></span></p>
  </div>
</div>
<?php else:?>
<div class='cell'>
  <div class='panel rounded ring-0 bg-canvas'>
    <div class="panel-heading">
      <div class="panel-title"><?php echo $title;?></div>
    </div>
    <div class='panel-body pt-0'>
      <div data-ride='table'>
        <table class='table table-condensed table-striped table-bordered table-fixed' id="stroyBugsList">
          <thead>
            <tr class='colhead text-center bg-canvas'>
              <th class="w-250px border"><?php echo $lang->pivot->bug->story;?></th>
              <th class='border'><?php echo $lang->pivot->bug->title;?></th>
              <th class="w-120px border"><?php echo $lang->pivot->bug->status;?></th>
              <th class="w-120px border"><?php echo $lang->pivot->bug->total;?></th>
            </tr>
          </thead>
          <?php if($stories):?>
          <tbody>
            <?php foreach($stories as $story):?>
            <?php if(!empty($story['bugList'])):?>
            <?php foreach($story['bugList'] as $key => $bug):?>
            <tr class="text-center">
              <?php if($story['total'] < 2 || ($story['total'] > 1 && !$key)):?>
              <td class='border' <?php if(!$key && $story['total'] > 1) echo 'rowspan="' . $story['total'] . '"';?>><?php echo $story['title'];?></td>
              <?php endif;?>

              <td class='border'><?php echo $bug->title;?></td>
              <td class='border'><?php echo $lang->bug->statusList[$bug->status];?></td>

              <?php if($story['total'] < 2 || ($story['total'] > 1 && !$key)):?>
              <td class="border" <?php if(!$key && $story['total'] > 1) echo 'rowspan="' . $story['total'] . '"';?>><?php echo $story['total'];?></td>
              <?php endif;?>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
            <?php endforeach;?>
          </tbody>
          <?php endif;?>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
<script>
function selectProduct(productID)
{
    var params = window.btoa('productID=' + productID);
    var link = $.createLink('pivot', 'preview', 'dimension=' + <?php echo $dimensionID;?> + '&group=' + <?php echo $groupID;?> + '&method=storylinkedbug&params=' + params);
    loadPage(link, '#pivotContent');
}

function selectModule(moduleID)
{
    var params = window.btoa('productID=<?php echo $productID;?>&moduleID=' + moduleID);
    var link = $.createLink('pivot', 'preview', 'dimension=' + <?php echo $dimensionID;?> + '&group=' + <?php echo $groupID;?> + '&method=storylinkedbug&params=' + params);
    loadPage(link, '#pivotContent');
}
</script>
