<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
.table{border: 1px solid #ddd}
</style>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->auditplan->{$auditplan->objectType} . ':' . $object . '<i class="icon-angle-right"></i> ' . $lang->auditplan->result;?></h2>
    </div>
    <?php if(!empty($checkList)):?>
    <?php echo html::hidden('hasDraft', !empty($draftResults));?>
      <table class="table table-fixed">
        <thead>
          <tr>
            <th class='text-left'><?php echo $lang->auditplan->content;?></th>
            <th class='w-80px text-left'><?php echo $lang->auditplan->result;?></th>
            <th class='w-120px text-left'><?php echo $lang->auditplan->checkedBy;?></th>
            <th class='w-120px text-left'><?php echo $lang->auditplan->date;?></th>
            <th class='w-200px text-left'><?php echo $lang->auditplan->comment;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($checkList as $list):?>
          <tr>
            <td><?php echo $list->title;?></td>
            <?php if(empty($results)):?>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            <?php else:?>
              <td><?php echo zget($lang->auditplan->resultList, $results[$list->id]->result, '');?></td>
              <td><?php echo zget($users, $results[$list->id]->checkedBy);?></td>
              <td><?php echo $results[$list->id]->checkedDate;?></td>
              <td><?php echo $results[$list->id]->comment;?></td>
            <?php endif;?>
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
