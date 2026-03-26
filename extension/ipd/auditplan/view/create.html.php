<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->auditplan->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-110px'><?php echo $lang->auditplan->process;?></th>
            <td><?php echo html::select('process', $processes, '', "class='form-control chosen' onchange=getActivity(this.value)");?></td>
            <td></td>
            <td></td>
          </tr>
          <tr <?php if(empty($project->multiple)) echo "class='hidden'";?>>
            <th><?php echo $lang->auditplan->execution;?></th>
            <td><?php echo html::select('execution', $executions, isset($executionID) ? $executionID : '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->auditplan->objectID;?></th>
            <td><?php echo html::select('activity', '', '', "class='form-control chosen'");?></td>
            <td><div class="required required-wrapper"></div><?php echo html::select('output', '', '', "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->auditplan->date;?></th>
            <td><?php echo html::input('checkDate', '', "class='form-control form-date'");?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->auditplan->assignedTo;?></th>
            <td><?php echo html::select('assignedTo', $users, '', "class='form-control chosen'");?></td>
            <td></td>
            <td></td>
          </tr>
          <tr id="commentBox">
            <th><?php echo $lang->auditplan->comment?></th>
            <td colspan='3'><?php echo html::textarea("comment", '', "class='form-control' rows=5");?></td>
          </tr>
          <tr>
            <td colspan='4' class='form-actions text-center'><?php echo html::submitButton() . html::backButton($lang->goback, "data-app='{$app->tab}'");?></td>
          </tr>
        </tbody>
    </form>
  </div>
</div>
<script>
function getActivity(processID)
{
    var link = createLink('auditplan', 'ajaxGetActivity', "projectID=<?php echo $projectID?>&processID=" + processID + '&i=0&from=<?php echo $from;?>');
    $.post(link, function(data)
    {
        $('#activity').replaceWith(data);
        $('#activity_chosen').remove();
        $('#activity').chosen();
    })
}

function getOutput(obj)
{
    var link = createLink('auditplan', 'ajaxGetOutput', "projectID=<?php echo $projectID?>&activityID=" + obj.value);
    $.post(link, function(data)
    {
        $('#output').replaceWith(data);
        $('#output_chosen').remove();
        $('#output').chosen();
    })
}

getActivity($('#process').val());
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
