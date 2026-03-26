<style>
#mainContent > .side-col.col-lg{width: 235px}
.hide-sidebar #sidebar{width: 0 !important}
</style>
<div class='bg-canvas p-2' id='conditions'>
  <div class='input-group' style='width: 200px'>
    <?php echo html::select('product', $products, $productID, 'onchange="selectProduct(this.value);" class="form-control chosen"')?>
  </div>
</div>
<?php if(empty($modules)):?>
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
        <table class='table table-condensed table-striped table-bordered tablesorter table-fixed active-disabled' id="testCaseList">
          <thead>
            <tr class='colhead text-center bg-canvas'>
              <th class='border'><?php echo $lang->pivot->moduleName;?></th>
              <th class='border'><?php echo $lang->pivot->case->total;?></th>
              <th class='border'><?php echo $lang->testcase->resultList['pass'];?></th>
              <th class='border'><?php echo $lang->testcase->resultList['fail'];?></th>
              <th class='border'><?php echo $lang->testcase->resultList['blocked'];?></th>
              <th class='border'><?php echo $lang->pivot->case->run;?></th>
              <th class='border'><?php echo $lang->pivot->case->passRate;?></th>
            </tr>
          </thead>
          <?php if($modules):?>
          <tbody>
            <?php $allTotal = $allPass = $allFail = $allBlocked = $allRun = 0;?>
            <?php foreach($modules as $module):?>
            <tr class="text-center">
              <?php
              $allTotal += $module->total;
              $allPass += $module->pass;
              $allFail += $module->fail;
              $allBlocked += $module->blocked;
              $allRun += $module->run;
              ?>
              <td class='border'><?php echo $module->name;?></td>
              <td class='border'><?php echo $module->total;?></td>
              <td class='border'><?php echo $module->pass;?></td>
              <td class='border'><?php echo $module->fail;?></td>
              <td class='border'><?php echo $module->blocked;?></td>
              <td class='border'><?php echo $module->run;?></td>
              <td class='border'><?php echo $module->run ? round(($module->pass / $module->run) * 100, 2) . '%' : 'N/A';?></td>
            </tr>
            <?php endforeach;?>
            <tr class="text-center">
              <td class='border'><?php echo $lang->pivot->total;?></td>
              <td class='border'><?php echo $allTotal;?></td>
              <td class='border'><?php echo $allPass;?></td>
              <td class='border'><?php echo $allFail;?></td>
              <td class='border'><?php echo $allBlocked;?></td>
              <td class='border'><?php echo $allRun;?></td>
              <td class='border'><?php echo $allRun ? round(($allPass / $allRun) * 100, 2) . '%' : 'N/A';?></td>
            </tr>
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
    var link = $.createLink('pivot', 'preview', 'dimension=' + <?php echo $dimensionID;?> + '&group=' + <?php echo $groupID;?> + '&method=testcase&params=' + params);
    loadPage(link, '#pivotContent');
}
</script>
