<style>
.plan{width:180px; margin:5px; padding:5px 0px; float:left; border:1px #eee solid;}
.roadmap-wrap{position: relative; overflow-x:auto}
</style>
<div class='bg-canvas p-2' id='conditions'>
  <div class='check-list-inline'>
    <div class="checkbox-primary">
      <input type="checkbox" name="closedProduct" value="closedProduct" id="closedProduct" onchange='loadRoadmap()' <?php if(strpos($conditions, 'closedProduct') !== false) echo "checked='checked'"?> />
      <label for="closedProduct"><?php echo $lang->pivot->closedProduct?></label>
    </div>
  </div>
</div>
<?php if(empty($products)):?>
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
      <div class='roadmap-wrap' data-ride='table'>
        <table id="roadmap" class='table table-condensed table-striped table-bordered table-fixed active-disabled'>
          <thead>
            <tr class='colhead text-left bg-canvas'>
              <th class="border" style="width: 200px;"><?php echo $lang->pivot->product;?></th>
              <th class='border'><?php echo $lang->pivot->plan;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($products as $productID => $product):?>
            <tr class="text-center">
              <td class= 'border' title="<?php echo $product?>"><?php echo $product?></td>
              <td class='border'>
                <?php if(!empty($plans[$productID])):?>
                <?php foreach($plans[$productID] as $plan):?>
                <div class='plan'>
                  <div class='text-ellipsis' title='<?php echo $plan->title;?>' ><?php echo $plan->title?></div>
                  <div>
                  <?php
                  if($plan->begin == $config->productplan->future and $plan->end == $config->productplan->future)
                  {
                      echo $lang->pivot->future;
                  }
                  else
                  {
                      if($plan->begin == $config->productplan->future) $plan->begin = $lang->pivot->future;
                      if($plan->end == $config->productplan->future) $plan->end = $lang->pivot->future;
                      echo $plan->begin . ' ~ ' . $plan->end;
                  }
                  ?>
                  </div>
                </div>
                <?php endforeach;?>
                <?php endif;?>
              </td>
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
function loadRoadmap()
{
    var conditions = '';
    $('#conditions input[type=checkbox]').each(function(i)
    {
        if($(this).prop('checked')) conditions += $(this).val() + ',';
    })
    conditions = conditions.substring(0, conditions.length - 1);

    var params = window.btoa('conditions=' + conditions);
    var link = $.createLink('pivot', 'preview', 'dimension=' + <?php echo $dimensionID;?> + '&groupID=' + <?php echo $groupID;?> + '&method=roadmap&params=' + params);
    loadPage(link, '#pivotContent');
}
</script>
