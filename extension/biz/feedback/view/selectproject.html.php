<?php echo html::hidden('feedbackID', '');?>
<div class="modal fade" id="toTask">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $lang->feedback->selectProjects;?></h4>
      </div>
      <div class="modal-body">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->feedback->project;?></th>
            <td class='required'><?php echo html::select('taskProjects', $projects, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th id='executionHead'><?php echo $lang->feedback->execution;?></th>
            <td class='required'><?php echo html::select('executions', '', '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <td colspan='2' class='text-center'>
              <?php echo html::commonButton($lang->feedback->nextStep, "id='taskProjectButton'", 'btn btn-primary btn-wide');?>
              <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-default btn-wide');?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
function getFeedbackID(obj)
{
    var feedbackID = $(obj).attr("data-id");
    $('#feedbackID').val(feedbackID);
    $('#taskProjects').change();
    getProjects(obj);
    getExecutions(0);
}

function getProjects(obj)
{
    var productID = $(obj).attr("data-product");
    var link      = createLink('feedback', 'ajaxGetProjects', 'productID=' + productID + '&field=taskProjects&onchange=&getHTML=1');

    $.post(link, function(data)
    {
        $('#taskProjects').replaceWith(data);
        $('#taskProjects_chosen').remove();
        $('#taskProjects').chosen();
    })
}

function getExecutions(projectID)
{
    if(projectID)
    {
        var langLink = createLink('feedback', 'ajaxGetExecutionLang', 'projectID=' + projectID);
        $.post(langLink, function(executionLang)
        {
            $('#executionHead').html(executionLang);
        })
    }

    var link = createLink('feedback', 'ajaxGetExecutions', 'projectID=' + projectID + '&getHTML=1');

    $.post(link, function(data)
    {
        $('#executions').replaceWith(data);
        $('#executions_chosen').remove();
        $('#executions').chosen();
    })
}

$('#taskProjectButton').on('click', function()
{
    var projectID   = $('#taskProjects').val();
    var executionID = $('#executions').val();
    var feedbackID  = $('#feedbackID').val();
    var executionID = executionID ? parseInt(executionID) : 0;

    if(projectID && executionID)
    {
        location.href = createLink('task', 'create', 'executionID=' + executionID + '&storyID=0&moduleID=' + moduleID + '&taskID=0&todoID=0&extra=projectID=' + projectID + ',feedbackID=' + feedbackID) + '#app=feedback';
    }
    else if(!projectID)
    {
        alert('<?php echo $lang->feedback->noProject;?>');
    }
    else
    {
        alert('<?php echo $lang->feedback->noExecution;?>');
    }
});
</script>
