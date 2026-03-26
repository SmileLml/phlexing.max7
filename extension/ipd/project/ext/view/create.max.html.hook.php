<style>
<?php if(isset($copyType) and $copyType != 'part'):?>
#productsBox .input-group > .input-group-addon{display:none !important;}
<?php endif;?>
.copy-container .nav li .line
{
    background: url('<?php echo $config->webRoot;?>theme/default/images/project/default-line.png') no-repeat;
    background-size: 100%;
}
.copy-container .nav li.active .line
{
    background: url('<?php echo $config->webRoot;?>theme/default/images/project/active-line.png') no-repeat;
    background-size: 100%;
}
.all-data .all-item .success-icon
{
    background: url('<?php echo $config->webRoot;?>theme/default/images/project/default-success.png') no-repeat;
    background-size: contain;
    vertical-align: middle;
}
.all-data.checked .all-item .success-icon
{
    background: url('<?php echo $config->webRoot;?>theme/default/images/project/active-success.png') no-repeat;
    background-size: contain;
}
#maxCopyProjectModal .modal-dialog {width: 780px}
</style>
<div class='modal fade modal-scroll-inside' id='maxCopyProjectModal'>
  <div class='modal-dialog'>
    <div class='modal-body'>
      <button type='button' class='close' data-dismiss='modal'><i class="icon icon-close"></i></button>
      <div class='copy-container'>
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="###" data-target="#tabContent1" data-toggle="tab" id="firstTab"><i class="number">1</i><span><?php echo $this->lang->project->copyProject->select ?></span></a>
            <div class="line"></div>
          </li>
          <li>
            <a href="###" data-toggle="tab"><i class="number">2</i><span><?php echo $this->lang->project->copyProject->confirmData ?></span></a>
            <div class="line"></div>
          </li>
          <li>
            <a href="###"><i class="number">3</i><span><?php echo $this->lang->project->copyProject->improveData ?></span></a>
            <div class="line"></div>
          </li>
          <li>
            <a href="###"><i class="number">4</i><span><?php echo $this->lang->project->copyProject->completeData ?></span></a>
            <div class="line"></div>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade active in" id="tabContent1">
            <div class="content">
              <div class="copy-title"><?php echo $lang->project->copyProject->selectPlz ?></div>
              <div class="select-box">
                <?php if($this->config->systemMode == 'ALM'):?>
                <div class="program">
                  <?php echo html::select('parent', array('' => '') + $programListSet, '', "class='form-control chosen' data-placeholder='{$lang->project->copyProject->selectProgram}' onchange='setProgram(\"program\")'");?>
                </div>
                <?php endif;?>
                <div class="project">
                  <?php echo html::select('selectCopyprojectID', array(''), '', "class='form-control chosen' data-placeholder='{$lang->project->copyProject->selectProjectPlz}' onchange='setProgram(\"project\")'");?>
                </div>
              </div>
              <div class="replace-list">
              </div>
            </div>
            <div class="footer-btns">
              <a class="btn btn-primary next-btn" data-target="#tabContent2" data-toggle="tab"><?php echo $this->lang->project->nextStep ?></a>
              <button class="btn cancel-btn" type="button"><?php echo $this->lang->project->copyProject->cancel ?></button>
              <?php echo html::hidden('choseCopyprojectName', ''); ?>
            </div>
          </div>
          <div class="tab-pane fade" id="tabContent2">
            <div class="content">
              <div class="copy-title"><?php echo $this->lang->project->copyProject->confirmCopyDataTip ?></div>
              <div class="copy-project-title text-ellipsis"></div>
              <div class="all-content">
                <div class="radio" id="allData">
                  <label>
                    <input type="radio" id="allCheckbox"> <?php echo $this->lang->project->copyProject->all ?>
                  </label>
                </div>
                <div class="all-data">
                  <?php $allList = $model == 'ipd' ? $lang->project->copyProject->ipdAllList : $lang->project->copyProject->allList;?>
                  <?php foreach($allList as $name): ?>
                  <?php
                  if($model == 'scrum' or $model == 'agileplus')
                  {
                    $executionTitle = sprintf($name, $this->lang->project->copyProject->sprint);
                  }
                  else
                  {
                    $executionTitle = sprintf($name, $this->lang->project->stage);
                  }
                  ?>
                  <div class="all-item text-ellipsis" title='<?php echo $executionTitle?>'>
                    <div class="success-icon"></div>
                    <?php echo $executionTitle?>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="basic-content">
                <div class="radio" id="basicData">
                  <label>
                    <input type="radio" id="basicCheckbox"> <?php echo $this->lang->project->copyProject->basic ?>
                  </label>
                </div>
                <div class="all-data">
                  <div class="all-item text-ellipsis">
                    <div class="success-icon"></div>
                    <?php echo $this->lang->project->copyProject->basicInfo ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="footer-btns">
              <button class="btn btn-primary complete-btn" type="button" id="goComplete"><?php echo $this->lang->project->copyProject->toComplete ?></button>
              <button class="btn cancel-btn" type="button"><?php echo $this->lang->project->copyProject->cancel ?></button>
              <?php echo html::hidden('choseCopyprojectID', ''); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php js::set('nextStep', $lang->project->nextStep);?>
<?php js::set('whitelist', $whitelist);?>
<?php js::set('copyType', isset($copyType) ? $copyType : '');?>
<?php js::set('PM', isset($PM) ? $PM : '');?>
<?php js::set('acl', isset($acl) ? $acl : 'private');?>
<?php js::set('auth', isset($auth) ? $auth : 'extend');?>
<script>
$(function()
{
    /* Init acl. */
    $('.aclBox #acl' + acl).prop('checked', true);

    /* Init whitelist. */
    var whitelistPicker = $('#whitelist').data('zui.picker');
    whitelistPicker.setValue(whitelist);

    if(copyType != 'previous') sessionStorage.removeItem('project');
    if(sessionStorage.getItem('project')) setPreviousData();

    $('#allCheckbox').attr('checked', 'true');
    $('.all-content .all-data').addClass('checked');

    setProgram('program');
});

$('#submit').click(function()
{
    if(copyType == 'all' || copyType == 'previous')
    {
        /* Add project sessionStorage. */
        sessionStorage.setItem("project", JSON.stringify($("form").serializeArray()))
    }
});

if(copyType != '')
{
  $('#submit').html(nextStep);
}

/* Init PM. */
$('#PM').val(PM);
/* Init auth. */
$('#auth' + auth).prop('checked', true);

/* Init previous project data. */
function setPreviousData()
{
    var copyProject = JSON.parse(sessionStorage. getItem('project'));
    var whitelist   = [];
    var plans       = [];
    var branches    = [];
    var newProduct  = [];
    var linkProduct = [];

    $.each(copyProject, function(field, project)
    {
        var id = '#' + project.name;

        if(project.name == 'desc')
        {
            var cmd = editor['desc'].edit.cmd;
            cmd.inserthtml(project.value);
        }
        else if(project.name == 'whitelist[]')
        {
            whitelist.push(project.value);
        }
        else if(project.name.indexOf('products[') != -1)
        {
            if(project.value == 0) return;

            var productID = project.name.replace('[', '').replace(']', '');
            if($('#' + productID).length)
            {
                $("#" + productID).val(project.value).trigger("chosen:updated");

                var index = productID.replace('products', '');
                if($("div#plan" + index).length == 0) $("#plansBox .row").append('<div class="col-sm-4" id="plan' + index + '"></div>');
            }
            else
            {
                newProduct[productID] = project.value;
            }
            linkProduct.push(productID);
        }
        else if(project.name.indexOf('plans[') != -1)
        {
            var planBoxID = project.name.substr(0, project.name.indexOf(']') + 1).replace('[', '').replace(']', '');
            if(typeof plans[planBoxID] == 'undefined') plans[planBoxID] = [];
            plans[planBoxID].push(project.value);
        }
        else if(project.name.indexOf('branch[') != -1)
        {
            var branchBoxID = project.name.substr(0, project.name.indexOf(']') + 1).replace('[', '').replace(']', '');
            if(typeof branches[branchBoxID] == 'undefined') branches[branchBoxID] = [];
            branches[branchBoxID].push(project.value);
        }
        else if(project.name == 'PM')
        {
            $(id).val(project.value).trigger("chosen:updated");
        }
        else if(project.name == 'future')
        {
            $('#future').prop("checked", true).change();
        }
        else if(project.name == 'acl')
        {
            var aclBoxID = "#" + project.name + project.value;
            $(aclBoxID).prop("checked", true).click();
        }
        else if(project.name == 'auth' || project.name == 'delta')
        {
            $("#" + project.name + project.value).prop("checked", true);
        }
        else
        {
            $(id).val(project.value);
        }
    });

    /* Add new link product. */
    for(newProductBoxID in newProduct)
    {
        if(newProduct[newProductBoxID] == 0) return;
        $('td.productsBox').last().find('div[id^="plan"]').find('a.addLine').click();
        $("#" + newProductBoxID).val(newProduct[newProductBoxID]).trigger("chosen:updated");
    }

    /* Delete unlink product. */
    $('td.productsBox').each(function()
    {
        var productBoxID = $(this).find('select[id^=products]').attr('id');
        if(!linkProduct.includes(productBoxID)) $(this).closest('tr').remove();
    });

    /* Set branch and plan. */
    for(index in linkProduct)
    {
        $('#' + linkProduct[index]).change();

        for(branchBox in branches) $('#' + branchBox).val(branches[branchBox]).trigger("chosen:updated").change();

        setTimeout(function()
        {
            for(planBox in plans) $('#' + planBox).val(plans[planBox]).trigger("chosen:updated");
        }, 500);
    }

    /* Init whitelist. */
    var whitelistPicker = $('#whitelist').data('zui.picker');
    whitelistPicker.setValue(whitelist);
}

function dataToggleClass(action,className)
{
    if (action == 'add') {
        $(className).addClass('checked')
    } else {
        $(className).removeClass('checked')
    }
}
$("#allData").click(function(e)
{
    $('#basicCheckbox').removeAttr('checked');
    dataToggleClass('remove', '.basic-content .all-data')
    dataToggleClass('add', '.all-content .all-data')
})
$("#basicData").click(function(e)
{
    $('#allCheckbox').removeAttr('checked');
    dataToggleClass('remove', '.all-content .all-data')
    dataToggleClass('add', '.basic-content .all-data')

})
$('.copy-container li:nth-child(2) a').click(function(e)
{
    e.stopPropagation();
})
$('#tabContent1 .next-btn').click(function(e)
{
    /* Remove all data item when copy no multiple project. */
    $('#tabContent2 .all-content').removeClass('hidden');
    $('#tabContent2 #allData #allCheckbox').click();
    if($('.replace-item input[name="projects"]:checked').attr('data-multiple') == 0)
    {
        $('#tabContent2 .all-content').addClass('hidden');
        $('#tabContent2 #basicData #basicCheckbox').click();
    }

    $('.copy-container li:first-child').removeClass('active');
    $('.copy-container li:nth-child(2)').addClass('active');
})
$('.cancel-btn,.complete-btn').click(function(e)
{
    $('#maxCopyProjectModal').modal('hide');
})
$('.open-btn').click(function(e)
{
    $('#firstTab').click();
})

function setProgram(type)
{
    var programID = $('#maxCopyProjectModal #parent').length > 0 ? $('#maxCopyProjectModal #parent').val() : 0;
    var projectID = (type == 'project') ? $('#maxCopyProjectModal #selectCopyprojectID').val() : 0;
    var $copyprojectID = $("#maxCopyProjectModal #selectCopyprojectID");
    var link = createLink('project', 'ajaxLoadProject', 'programID=' + programID + '&projectID=' + projectID + '&model=' + model);
    $.getJSON(link, function(projects)
    {
        var $copyprojectID = $("#maxCopyProjectModal #selectCopyprojectID");
        var $replaceList   = $("#maxCopyProjectModal .replace-list");
        var i = 0;
        if(type == 'program')
        {
            $copyprojectID.find('option').remove();
            $copyprojectID.append("<option value='' title='' data-keys=''></option>");
        }
        $replaceList.find('.radio').remove();

        $('#tabContent1 .footer-btns a.next-btn').removeAttr('disabled');
        if(projects.length == 0)
        {
            $('#choseCopyprojectID').val('');
            $('#tabContent1 .footer-btns a.next-btn').attr('disabled', 'disabled');
        }

        $.each(projects, function(id, project)
        {
            if(id == '0') return;

            i += 1;
            if(type == 'program') $copyprojectID.append("<option value='" + id + "' data-keys='" + project.name + "'>" + project.name + "</option>");
            if(i <= 10) $replaceList.append("<div class='radio'><label class='replace-item text-ellipsis'><input type='radio' name='projects' title='" + project.name + "' value='" + id + "' data-multiple='" + project.multiple + "' /><i class='icon icon-project'></i>" + project.name + "</label></div>");
        })
        if(type == 'program') $copyprojectID.trigger("chosen:updated");
        $replaceList.find('input[name=projects]:first').click();
        return true;
    })
}

$(document).on('click', '.replace-item input', function()
{
    $('#choseCopyprojectID').val($(this).val())

    $('#tabContent2 .copy-project-title').html('<i class="icon icon-project"></i>' + $(this).attr('title'))
    $('#tabContent2 .copy-project-title').attr('title', $(this).attr('title'))
})

$('#goComplete').click(function(e)
{
    var copyProjectID = $('#choseCopyprojectID').val();

    if($('#basicCheckbox').prop("checked"))
    {
        location.href = createLink('project', 'create', 'model=' + model + '&programID=' + programID + '&copyProjectID=' + copyProjectID + '&extra=copyType=part');
    }
    else
    {
        location.href = createLink('project', 'copyproject', 'model=' + model + '&programID=' + programID + '&copyProjectID=' + copyProjectID + '&extra=copyType=all');
    }
})
</script>
