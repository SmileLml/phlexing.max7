<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<style>
[id^=process] + .chosen-container-single .chosen-single>span{margin-right:10px;}
</style>
<div id="mainContent" class="main-content fade">
  <div class="main-header clearfix">
    <h2 class="pull-left">
      <?php echo $lang->auditplan->batchEdit;?>
    </h2>
  </div>
  <form method='post' class='load-indicator batch-actions-form form-ajax' enctype='multipart/form-data' id="batchCreateForm">
    <div class="table-responsive">
      <table class="table table-form" id="tableBody">
      <?php $hiddenExecution = empty($project->multiple) ? 'hidden' : '';?>
        <thead>
          <tr class='text-center'>
            <th class='w-30px'><?php echo $lang->idAB;?></th>
            <th class='w-150px <?php echo $hiddenExecution;?>'><?php echo $lang->auditplan->execution;?></th>
            <th class='w-120px'><?php echo $lang->auditplan->process;?></th>
            <th class='required w-400px' colspan='2'><?php echo $lang->auditplan->objectID;?></span></th>
            <th class='w-200px'><?php echo $lang->auditplan->date;?></th>
            <th class='w-120px'><?php echo $lang->auditplan->assignedTo;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($auditplans as $auditplan):?>
          <?php $auditplanID = $auditplan->id;?>
          <tr data-id='<?php echo $auditplanID;?>'>
            <td><?php echo $auditplanID;?></td>
            <td <?php if(!empty($hiddenExecution)) echo "class='{$hiddenExecution}'";?>><?php echo html::select("execution[$auditplanID]", $executions, $auditplan->execution, "class='form-control chosen'");?></td>
            <td><?php echo html::select("process[$auditplanID]", $processes, $auditplan->process, "class='form-control chosen' onchange=changeActivity(this)");?></td>
            <td><?php echo html::select("activity[$auditplanID]", '', '', "class='form-control chosen'");?></td>
            <td><?php echo html::select("output[$auditplanID]", '', '', "class='form-control chosen'");?></td>
            <td>
              <?php echo html::hidden("dateType[$auditplanID]", $auditplan->dateType);?>
              <?php echo html::input("checkDate[$auditplanID]", helper::isZeroDate($auditplan->checkDate) ? '' : $auditplan->checkDate, "class='form-control form-date form-date'");?>
            </td>
            <td><?php echo html::select("assignedTo[$auditplanID]", $users, $auditplan->assignedTo, "class='form-control chosen'");?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <?php $colspan = $hiddenExecution ? 6 : 7;?>
            <td colspan='<?php echo $colspan;?>' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton($lang->goback, "data-app='{$app->tab}'");?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php js::set('objectTypeList', $objectTypeList);?>
<?php js::set('objectIdList', $objectIdList);?>
<?php js::set('activityIdList', $activityIdList);?>
<script>
/**
 * Change activity.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function changeActivity(obj)
{
    var id = $(obj).closest('tr').attr('data-id');
    var seletedValue = obj.value;

    var processID = $('#process' + id).val();

    $.get(createLink('auditplan', 'ajaxGetActivity', "projectID=<?php echo $projectID?>&processID=" + seletedValue + '&i=' + id + "&from=<?php echo $from;?>"), function(data)
    {
        $('#activity' + id).replaceWith(data);
        $('#activity' + id + '_chosen').remove();
        $('#activity' + id).chosen();

        getOutput($('#activity' + id));
    })
}

/**
 * Get activity.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function getActivity(obj)
{
    var id = $(obj).closest('tr').attr('data-id');
    var value = $(obj).val();
    var activityID = activityIdList[id];

    var link = createLink('auditplan', 'ajaxGetActivity', "projectID=<?php echo $projectID?>&processID=" + value + '&i=' + id + "&from=<?php echo $from;?>");
    $.get(link, function(data)
    {
        $('#activity' + id).replaceWith(data);
        $('#activity' + id + '_chosen').remove();
        $('#activity' + id).val(activityID);
        $('#activity' + id).chosen();

        initOutput(id, activityID);
    })
}

/**
 * Init output
 *
 * @param  int $id
 * @param  int $activityID
 * @access public
 * @return void
 */
function initOutput(id, activityID)
{
    var link = createLink('auditplan', 'ajaxGetOutput', "projectID=<?php echo $projectID?>&activityID=" + activityID + '&i=' + id);
    $.get(link, function(data)
    {
        $('#output' + id).replaceWith(data);
        $('#output' + id + '_chosen').remove();

        var objectType = objectTypeList[id];
        var objectID   = objectIdList[id];
        if(objectType == 'zoutput') $('#output' + id).val(objectID);

        $('#output' + id).chosen();
    })
}

/**
 * Get output.
 *
 * @param  int $id
 * @param  int $activityID
 * @access public
 * @return void
 */
function getOutput(obj)
{
    var id = $(obj).attr('id').replace(/[^0-9]/ig, '');
    var link = createLink('auditplan', 'ajaxGetOutput', "projectID=<?php echo $projectID?>&activityID=" + obj.value + '&i=' + id);
    $.get(link, function(data)
    {
        $('#output' + id).replaceWith(data);
        $('#output' + id + '_chosen').remove();
        $('#output' + id).chosen();
    })
}

$('select[id^="process"]').each(function()
{
    getActivity(this);
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
