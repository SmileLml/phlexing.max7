<?php
/**
 * The edit of auditcl module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     auditcl
 * @version     $Id: edit.html.php 4903 2020-09-04 09:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('objectType', $auditcl->objectType);;?>
<?php js::set('objectID', $auditcl->objectID);;?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->auditcl->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
         <tr>
           <th><?php echo $lang->auditcl->practiceArea;?></th>
           <td><?php echo html::select('practiceArea', $this->lang->auditcl->practiceAreaList, $auditcl->practiceArea, "class='form-control chosen'");?></td>
           <td></td>
           <td></td>
         </tr>
         <tr>
           <th><?php echo $lang->auditcl->objectType;?></th>
           <td><?php echo html::select('type', $this->lang->auditcl->typeList, $auditcl->type, "class='form-control chosen'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->auditcl->objectID;?></th>
           <td>
             <?php echo html::select('process', $processes, $auditcl->process, "class='form-control chosen' onchange=changeProcess(this.value)");?>
           </td>
         </tr>
         <tr>
           <th><?php echo $lang->auditcl->activeDoc;?></th>
           <td>
             <?php echo html::select('activity', $activities, $auditcl->objectType == 'activity' ? $auditcl->objectID : $auditcl->activity, "class='form-control chosen' onchange=changeActivity(this.value)");?>
           </td>
           <td>
             <?php echo html::select('zoutput', $zoutputs, $auditcl->objectType == 'zoutput' ? $auditcl->objectID : '', "class='form-control chosen'");?>
           </td>
         </tr>
         <tr>
           <th><?php echo $lang->auditcl->title;?></th>
           <td><?php echo html::input('title', $auditcl->title, "class='form-control'");?></td>
         </tr>
         <tr>
           <td colspan='3' class='form-actions text-center'>
             <?php echo html::hidden('model', $auditcl->model);?>
             <?php echo html::submitButton() . html::backButton();?>
           </td>
         </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

