<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->nc->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-110px'><?php echo $lang->nc->title;?></th>
            <td><?php echo html::input('title', $nc->title, "class='form-control'");?></td>
            <td></td>
            <td></td>
          </tr>
          <tr <?php if(empty($project->multiple)) echo "class='hidden'";?>>
            <th><?php echo $lang->nc->execution;?></th>
            <td><?php echo html::select('execution', $executions, $auditplan->execution, "class='form-control chosen' onchange=changeAuditplan(this)");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->auditplan;?></th>
            <td class='required'><?php echo html::select('auditplan', $auditplans, $auditplan->id, "class='form-control chosen' onchange=changeCheckList(this)");?></td>
          </tr>
          <tr>
            <th><?php echo $this->lang->auditplan->content;?></th>
            <td class='required'><?php echo html::select('listID', $checkList, $nc->listID, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $this->lang->auditplan->severity;?></th>
            <td class='required'><?php echo html::select('severity', $lang->nc->severityList, $nc->severity, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->type;?></th>
            <td><?php echo html::select('type', $lang->nc->typeList, $nc->type, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->assignedTo;?></th>
            <td><?php echo html::select('assignedTo', $users, $nc->assignedTo, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->deadline;?></th>
            <td><?php echo html::input('deadline', $nc->deadline, "class='form-control form-date'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', $nc->desc, "class='form-control'");?></td>
          </tr>
          <tr>
            <td colspan='4' class='form-actions text-center'><?php echo html::submitButton() . html::backButton($lang->goback, "data-app='{$app->tab}'");?></td>
          </tr>
        </tbody>
    </form>
  </div>
</div>
<script>
function changeAuditplan(obj)
{
    var link = createLink('auditplan', 'ajaxGetAuditplan', "executionID=" + obj.value);
    $.post(link, function(data)
    {
        $('#auditplan').replaceWith(data);
        $('#auditplan' + '_chosen').remove();
        $('#auditplan').chosen();
    })
}

function changeCheckList(obj)
{
    var link = createLink('auditplan', 'ajaxGetCheckList', "auditplanID=" + obj.value);
    $.post(link, function(data)
    {
        $('#listID').replaceWith(data);
        $('#listID' + '_chosen').remove();
        $('#listID').chosen();
    })
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
