<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class='pull-right'>
    <?php if(common::canModify('project', $project)) common::printLink('cm', 'create', "project=$projectID", "<i class='icon icon-plus'></i>" . $lang->cm->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade in">
  <div class="main-col">
    <?php if(empty($baselines)):?>
    <div class="table-empty-tip">
      <p> 
        <span class="text-muted"><?php echo $lang->cm->noData;?></span>
        <?php if(common::canModify('project', $project) && common::hasPriv('cm', 'create')):?>
        <?php echo html::a($this->createLink('cm', 'create', "progrm=$projectID"), "<i class='icon icon-plus'></i> " . $lang->cm->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' method='post'>
      <table class="table has-sort-head table-fixed">
        <thead>
          <tr>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='w-150px'><?php echo $lang->cm->object;?></th>
            <th><?php echo $lang->cm->title;?></th>
            <th class='w-160px'><?php echo $lang->cm->version;?></th>
            <th class='w-120px'><?php echo $lang->cm->createdBy;?></th>
            <th class='w-120px'><?php echo $lang->cm->createdDate;?></th>
            <th class='w-80px'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <?php foreach($baselines as $baseline):?>
        <tr>
          <td><?php echo $baseline->id;?></td>
          <td><?php echo zget($lang->baseline->objectList, $baseline->category);?></td>
          <td><?php echo html::a(helper::createLink('cm', 'view', "baselineID=$baseline->id"), $baseline->title);?></td>
          <td><?php echo $baseline->version;?></td>
          <td><?php echo zget($users, $baseline->createdBy);?></td>
          <td><?php echo $baseline->createdDate;?></td>
          <td class='c-actions'>
            <?php common::printIcon('cm', 'edit', "id=$baseline->id", $baseline, 'edit');?>
            <?php common::printIcon('cm', 'delete', "id=$baseline->id", $baseline, 'closed', 'trash', 'hiddenwin');?>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
      <div class='table-footer'> <?php $pager->show('right', 'pagerjs');?> </div>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
