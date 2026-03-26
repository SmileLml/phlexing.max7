<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->nc->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-110px'><?php echo $lang->nc->title;?></th>
            <td class='required'><?php echo html::input('title', '', "class='form-control'");?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->type;?></th>
            <td><?php echo html::select('type', $lang->nc->typeList, '', "class='form-control chosen'");?></td>
          </tr>
          <tr <?php if(empty($project->multiple)) echo "class='hidden'";?>>
            <th><?php echo $lang->nc->execution;?></th>
            <td><?php echo html::select('execution', $executions, isset($executionID) ? $executionID : '', "class='form-control chosen' onchange=changeAuditplan(this)");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->auditplan;?></th>
            <td class='required'><?php echo html::select('auditplan', '', '', "class='form-control chosen' onchange=changeCheckList(this)");?></td>
          </tr>
          <tr>
            <th><?php echo $this->lang->auditplan->content;?></th>
            <td class='required'><?php echo html::select('listID', '', '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $this->lang->auditplan->severity;?></th>
            <td class='required'><?php echo html::select('severity', $lang->nc->severityList, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <?php echo html::hidden('mode', 'normal');?>
            <td colspan='4' class='form-actions text-center'><?php echo html::submitButton() . html::backButton($lang->goback, "data-app='{$app->tab}'");?></td>
          </tr>
        </tbody>
    </form>
  </div>
</div>
<?php js::set('projectID', $projectID)?>
<script>

function changeAuditplan(obj)
{
    var link = createLink('auditplan', 'ajaxGetAuditplan', "projectID=" + projectID + "&executionID=" + obj.value);
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

$('select[id^="execution"]').each(function()
{
    changeAuditplan(this);
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
