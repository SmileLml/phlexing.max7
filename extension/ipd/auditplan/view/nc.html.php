<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
.table{border: 1px solid #ddd}
</style>
<?php
$object = $this->auditplan->getObjectName($auditplan->objectType, $auditplan->objectID);
?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->auditplan->{$auditplan->objectType} . ':' . $object . '<i class="icon-angle-right"></i> ' . $lang->auditplan->nc;?></h2>
    </div>
    <?php if(!empty($ncs)):?>
    <table class='table'>
      <thead> 
        <tr>
          <th class='w-50px'><?php echo $lang->auditplan->id;?></th>
          <th class='w-160px'><?php echo $lang->auditplan->content;?></th>
          <th><?php echo $lang->auditplan->name;?></th>
          <th class='w-60px'><?php echo $lang->auditplan->status;?></th>
          <th class='w-80px'><?php echo $lang->auditplan->severity;?></th>
          <th class='w-80px'><?php echo $lang->auditplan->createdBy;?></th>
          <th class='w-100px'><?php echo $lang->auditplan->createdDate;?></th>
        </tr>
      </thead> 
      <tbody> 
        <?php foreach($ncs as $nc):?>
        <tr>
         <td><?php echo $nc->id;?></td>
         <td><?php echo $this->auditplan->getListName($nc->listID);?></td>
         <td><?php echo html::a($this->createLink('nc', 'view', "ncID=$nc->id"), $nc->title);?></td>
         <td><?php echo zget($lang->nc->statusList, $nc->status);?></td>
         <td><?php echo zget($lang->nc->severityList, $nc->severity);?></td>
         <td><?php echo zget($users, $nc->createdBy);?></td>
         <td><?php echo substr($nc->createdDate, 0, 10);?></td>
        </tr>
        <?php endforeach;?>
      </tbody> 
    </table>
    <?php else:?>
    <div class='table-empty-tip'><?php echo $lang->auditplan->noData;?></div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
