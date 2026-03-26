<style>
.icon-code-fork:before {transform: rotate(180deg) rotateY(179deg);}
td {padding: 8px 0px !important;}
</style>
<div class='bg-canvas p-2' id='conditions'>
  <div class='input-group' style='width: 200px'>
    <?php echo html::select('product', $products, $productID, 'onchange="selectProduct(this.value);" class="form-control chosen"')?>
  </div>
</div>
<?php if(empty($bugs)):?>
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
        <table class='table table-condensed table-striped table-bordered table-fixed' id="buildList">
          <thead>
            <?php ksort($lang->bug->severityList);?>
            <?php unset($lang->bug->typeList[''])?>
            <?php unset($lang->bug->typeList['designchange'])?>
            <?php unset($lang->bug->typeList['newfeature'])?>
            <?php unset($lang->bug->typeList['trackthings'])?>
            <tr class='colhead text-center bg-canvas'>
              <th class='border' style='width: 100px' rowspan="2"><?php echo $lang->pivot->project;?></th>
              <th class='border' style='width: 150px' rowspan="2"><?php echo $lang->pivot->buildTitle;?></th>
              <th class='border' style='width: 100px' colspan="<?php echo count($lang->bug->severityList)?>"><?php echo $lang->pivot->severity;?></th>
              <th class='border' style="width: 450px" colspan="<?php echo count($lang->bug->typeList);?>"><?php echo $lang->pivot->bugType;?></th>
              <th class='border' style='width: 100px' colspan="3"><?php echo $lang->pivot->bugStatus;?></th>
            </tr>
            <tr class="text-center colhead bg-canvas">
              <?php foreach($lang->bug->severityList as $key => $severity):?>
              <td class='border' title='<?php echo $severity;?>'><?php echo $severity;?></td>
              <?php endforeach;?>
              <?php foreach($lang->bug->typeList as $bugTypeKey => $bugType): ?>
              <td class='border' title='<?php echo $bugType;?>'><?php echo $bugType?></td>
              <?php endforeach; ?>
              <td class='border'><?php echo $lang->bug->statusList['active'];?></td>
              <td class='border'><?php echo $lang->bug->statusList['resolved'];?></td>
              <td class='border'><?php echo $lang->bug->statusList['closed'];?></td>
            </tr>
          </thead>
          <?php if($bugs):?>
          <tbody>
            <?php foreach($bugs as $key => $projectBuilds):?>
            <tr class="text-center">
              <?php $count = count($projectBuilds);?>
              <td class="text-left border" style="padding: 8px !important;" rowspan="<?php echo $count;?>" title="<?php echo $projects[$key];?>"><?php echo $projects[$key];?></td>
              <?php foreach($projectBuilds as $buildId => $build):?>
              <td class='text-left border' style="padding: 8px !important;" title="<?php echo isset($builds[$buildId]) ? $builds[$buildId] : '';?>">
                <?php if(isset($builds[$buildId])):?>
                <span class='build'>
                <?php echo "<span class='buildName'>" . $builds[$buildId] . '</span>';?>
                <?php if(!$build['execution']):?>
                <span class='icon icon-code-fork text-muted' title=<?php echo $lang->build->integrated;?>></span>
                <?php endif;?>
                </span>
                <?php endif;?>
              </td>
              <?php foreach($lang->bug->severityList as $severity => $val):?>
              <td class='border'><?php echo isset($build['severity'][$severity]) ? $build['severity'][$severity] : 0;?></td>
              <?php endforeach;?>
              <?php foreach($lang->bug->typeList as $bugTypeKey => $bugTypeVal):?>
              <td class='border'><?php echo isset($build['type'][$bugTypeKey]) ? $build['type'][$bugTypeKey] : 0;?></td>
              <?php endforeach;?>
              <td class='border'><?php echo isset($build['status']['active'])   ? $build['status']['active']   : 0;?></td>
              <td class='border'><?php echo isset($build['status']['resolved']) ? $build['status']['resolved'] : 0;?></td>
              <td class='border'><?php echo isset($build['status']['closed'])   ? $build['status']['closed']   : 0;?></td>
            </tr>
            <?php
            if($count != 1) echo '<tr class="text-center">';
            $count --;
            ?>
            <?php endforeach;?>
            <?php endforeach;?>
            <tr class="text-center">
              <td colspan='2' class='text-center border'><?php echo $lang->pivot->total;?></td>
              <?php foreach($lang->bug->severityList as $key => $severity):?>
              <?php $total = isset($summary['severity'][$key]) ? count($summary['severity'][$key]) : 0;?>
              <td class='border' title='<?php echo $total?>'><?php echo $total;?></td>
              <?php endforeach;?>
              <?php foreach($lang->bug->typeList as $bugTypeKey => $bugType): ?>
              <?php $total = isset($summary['type'][$bugTypeKey]) ? count($summary['type'][$bugTypeKey]) : 0;?>
              <td class="border" title='<?php echo $total?>'><?php echo $total;?></td>
              <?php endforeach; ?>
              <?php $total = isset($summary['status']['active']) ? count($summary['status']['active']) : 0;?>
              <td class="border" title='<?php echo $total?>'><?php echo $total;?></td>
              <?php $total = isset($summary['status']['resolved']) ? count($summary['status']['resolved']) : 0;?>
              <td class="border" title='<?php echo $total?>'><?php echo $total;?></td>
              <?php $total = isset($summary['status']['closed']) ? count($summary['status']['closed']) : 0;?>
              <td class="border" title='<?php echo $total?>'><?php echo $total;?></td>
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
    var link = $.createLink('pivot', 'preview', 'dimension=' + <?php echo $dimensionID;?> + '&group=' + <?php echo $groupID;?> + '&method=build&params=' + params);
    loadPage(link, '#pivotContent');
}

$(function()
{
    $('td .build .icon-code-fork').each(function()
    {
        $build = $(this).closest('.build');
        $td = $(this).closest('td');
        if($td.width() < $build.width())
        {
            $build.find('.buildName').css('display', 'inline-block').css('width', $td.width() - $(this).width()).css('overflow', 'hidden').css('float', 'left');
        }
    })
})
</script>
