<div class='flex bg-canvas p-2 gap-3' id='conditions'>
  <div class='input-group w-1/4'>
    <span class='input-group-addon'><?php echo $lang->pivot->dept;?></span>
    <?php echo html::select('dept', $depts, $dept, "class='form-control chosen' onchange='changeParams()'");?>
  </div>
  <div class='input-group w-1/2'>
    <span class='input-group-addon'><?php echo $lang->pivot->bugAssignedDate;?></span>
    <div id='beginPicker' zui-create zui-create-datepicker="{defaultValue: '<?php echo $begin;?>', onChange: (e) => changeParams()}" ></div>
    <span class='input-group-addon'><?php echo $lang->pivot->to;?></span>
    <div id='endPicker' zui-create zui-create-datepicker="{defaultValue: '<?php echo $end;?>', onChange: (e) => changeParams()}"></div>
  </div>
</div>
<?php if(empty($userBugs)):?>
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
        <table class='table table-condensed table-striped table-bordered table-fixed' id="bugsummary">
          <thead>
            <tr class='colhead text-center bg-canvas'>
            <th class="border w-20"><?php echo $lang->bug->assignedTo;?></th>
            <th class="border w-20"><?php echo $lang->bug->id;?></th>
            <th class='border' title="<?php echo $lang->bug->title;?>"><?php echo $lang->bug->title;?></th>
            <th class="border w-16"><?php echo $lang->bug->pri;?></th>
            <th class="border w-20"><?php echo $lang->bug->severity;?></th>
            <th class="border w-20"><?php echo $lang->bug->openedBy;?></th>
            <th class="border w-28"><?php echo $lang->bug->openedDate;?></th>
            <th class="border w-28"><?php echo $lang->bug->assignedDate;?></th>
            <th class="border w-20"><?php echo $lang->bug->status;?></th>
          </tr>
          </thead>
          <tbody>
            <?php foreach($userBugs as $user => $bugs):?>
            <?php if(!isset($users[$user])) continue;?>
            <tr class="text-center">
              <td class="border text-top" rowspan="<?php echo count($bugs);?>"><?php echo zget($users, $user);?></td>
              <?php foreach($bugs as $id => $bug):?>
              <?php if($id != 0) echo "<tr class='text-center'>"?>
                <td class="border"><?php echo $bug->id;?></td>
                <td class="border text-left" title="<?php echo $bug->title;?>"><?php echo $bug->title;?></td>
                <td class="border"><span class='<?php echo 'pri' . $bug->pri?>'><?php echo zget($lang->bug->priList, $bug->pri);?></span></td>
                <td class="border"><span class='<?php echo 'severity' . $bug->severity?>'><?php echo zget($lang->bug->severityList, $bug->severity);?></span></td>
                <td class="border"><?php echo zget($users, $bug->openedBy);?></td>
                <td class="border"><?php echo substr($bug->openedDate, 0, 10);?></td>
                <td class="border"><?php echo substr($bug->assignedDate, 0, 10);?></td>
                <td class="border"><?php echo $lang->bug->statusList[$bug->status];?></td>
              <?php if($id != 0) echo "</tr>"?>
              <?php endforeach;?>
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
function changeParams()
{
    const beginPick = $('#beginPicker').zui();
    const endPick = $('#endPicker').zui();

    var dept  = $('#conditions').find('#dept').val();
    var begin = beginPick.$.value;
    var end   = endPick.$.value;
    if(begin.indexOf('-') != -1)
    {
        var beginarray = begin.split("-");
        var begin = '';
        for(i=0 ; i < beginarray.length ; i++)
        {
            begin = begin + beginarray[i];
        }
    }
    if(end.indexOf('-') != -1)
    {
        var endarray = end.split("-");
        var end = '';
        for(i=0 ; i < endarray.length ; i++)
        {
            end = end + endarray[i];
        }
    }

    var params = window.btoa('dept=' + dept + '&begin=' + begin + '&end=' + end);
    var link = $.createLink('pivot', 'preview', 'dimension=' + <?php echo $dimensionID;?> + '&group=' + <?php echo $groupID;?> + '&method=bugassignsummary&params=' + params);
    loadPage(link, '#pivotContent');
}
</script>
