<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<style>#batchEdit tbody td{text-align: left}</style>
<div class="main-content" id="mainContent">
  <div class="main-header">
    <h2><?php echo $lang->auditcl->batchEdit;?></h2>
  </div>
  <form class="load-indicator main-form" method="post" target="hiddenwin" id="batchEdit">
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr class="text-center">
            <th class="col-3 required"><?php echo $lang->auditcl->object;?></th>
            <th class="col-3 required"><?php echo $lang->auditcl->type;?></th>
            <th class="col-3 required"><?php echo $lang->auditcl->checkType;?></th>
            <th class="col-3 required"><?php echo $this->lang->auditcl->practiceArea?></th>
            <th class="col-3 required"><?php echo $this->lang->auditcl->objectType?></th>
            <th class="col-3 required"><?php echo $this->lang->auditcl->title?></th>
            <th class="col-3"><?php echo $this->lang->auditcl->assignedTo?></th>
          </tr>
        </thead>
        <tbody id="auditclList">
          <?php foreach($auditcls as $auditcl):?>
            <tr data-id="<?php echo $auditcl->id;?>" class="text-center">
              <td><?php echo html::input("process[$auditcl->id]", zget($processList, $auditcl->process), "class='form-control' disabled")?></td>
              <td><?php echo html::input("objectType[$auditcl->id]", zget($lang->auditcl->objectTypeList, $auditcl->objectType), "class='form-control' disabled")?></td>
              <td><?php echo html::input("activity[$auditcl->id]", zget($auditcl->objectType == 'activity' ? $activityList : $zoutputList, $auditcl->objectID), "class='form-control' disabled")?></td>
              <td><?php echo html::select("practiceArea[$auditcl->id]", $this->lang->auditcl->practiceAreaList, $auditcl->practiceArea, "class='form-control chosen'");?></td>
              <td><?php echo html::select("type[$auditcl->id]", $this->lang->auditcl->typeList, $auditcl->type, "class='form-control chosen'");?></td>
              <td><?php echo html::input("title[$auditcl->id]", $auditcl->title, "class='form-control'");?></td>
              <td><?php echo html::select("assignedTo[$auditcl->id]", $users, $auditcl->assignedTo, "class='form-control chosen'");?></td>
            </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="9" class="text-center form-actions">
              <?php echo html::submitButton($lang->save)?>
              <?php echo html::backButton()?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
