<?php
/**
 * The create of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     meeting
 * @version     $Id: create.html.php 4903 2021-06-10 13:53:59Z lyc $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php js::set('objectType', array_keys($config->meeting->objectTypeList));?>
<?php js::set('projectID', $projectID);?>
<?php js::set('noSprintPairs', $noSprintPairs);?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->meeting->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->meeting->project;?></th>
            <td><?php echo html::select('project', $projects, isset($projectID) ? $projectID : '', "class='form-control chosen' onchange='loadProjectExecutions(this.value)'");?></td>
          </tr>
          <tr <?php if(empty($project->multiple)) echo "class='hidden'";?>>
            <th><?php echo $lang->meeting->execution;?></th>
            <td><?php echo html::select('execution', $executions, isset($executionID) ? $executionID : '', "class='form-control chosen' onchange='loadTeamMembers(this.value)'");?></td>
          </tr>
          <tr>
            <th class='w-110px'><?php echo $lang->meeting->date;?></th>
            <td><?php echo html::input('date', '', "class='form-control form-date'");?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <th class='w-110px'><?php echo $lang->meeting->room;?></th>
            <td><?php echo html::select('room', $rooms, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th class='w-110px'><?php echo $lang->meeting->begin;?></th>
            <td><?php echo html::input('begin', '', "class='form-control form-time'");?></td>
          </tr>
          <tr>
            <th class='w-110px'><?php echo $lang->meeting->end;?></th>
            <td><?php echo html::input('end', '', "class='form-control form-time'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->meeting->dept;?></th>
            <td><?php echo html::select('dept', $depts, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->meeting->mode;?></th>
            <td><?php echo html::select('mode', $lang->meeting->modeList, '', "class='form-control chosen'");?></td>
          </tr>
          <?php if($app->tab == 'project' and $project->model == 'waterfall'):?>
          <tr>
            <th><?php echo $lang->meeting->type;?></th>
            <td><?php echo html::select('type', $typeList, '', "class='form-control chosen'");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->meeting->objectType;?></th>
            <td><?php echo html::select('objectType', $config->meeting->objectTypeList, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->meeting->objectID;?></th>
            <td id='objectBox'><?php echo html::select('objectID', '', '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->meeting->host;?></th>
            <td><?php echo html::select('host', $users, $this->app->user->account, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->meeting->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control'");?></td>
          </tr>
          <tr class="mailtoBox">
            <th><?php echo $lang->meeting->participant;?></th>
            <td colspan="2">
              <div class="input-group" id='contactListGroup'>
                <?php echo html::select('participant[]', $users, '', "class='form-control picker-select' multiple");?>
                <?php echo $this->fetch('my', 'buildContactLists');?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->meeting->files;?></th>
            <td><?php echo $this->fetch('file', 'buildoldform', 'fileCount=1&percent=0.85');?></td>
          </tr>
          <tr>
            <td colspan='4' class='form-actions text-center'><?php echo html::submitButton() . html::backButton($lang->goback, "data-app='{$app->tab}'");?></td>
          </tr>
        </tbody>
    </form>
  </div>
</div>
<script>
function setMailto(field, value)
{
    var link = createLink('meeting', 'ajaxGetContactUsers', "listID=" + value);
    $.post(link, function(data)
    {
        $('#contactListGroup .picker-multi').remove();
        $('#participant').replaceWith(data);
        $('#participant').picker();
    })
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
