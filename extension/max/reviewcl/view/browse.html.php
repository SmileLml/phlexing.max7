<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<?php unset($lang->baseline->objectList['']);?>
<div id='mainMenu' class='clearfix'>
  <div id="sidebarHeader">
    <div class="title">
      <?php
      if($object)
      {
          echo zget($lang->baseline->objectList, $object);
          echo html::a(inlink($method, "type={$type}&object="), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      else
      {
          echo $lang->reviewcl->browseByType;
      }
      ?>
    </div>
  </div>
  <div class="toolbar">
  <div class='btn-toolbar pull-left'>
    <?php include './menu.html.php';?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php if(common::hasPriv('reviewcl', 'create') and common::hasPriv('reviewcl', 'batchCreate')):?>
    <div class='btn-group dropdown'>
      <?php
      $actionLink = $this->createLink('reviewcl', 'create', "type=$type&object=$object");
      echo html::a($actionLink, "<i class='icon icon-plus'></i> {$lang->reviewcl->create}", '', "class='btn btn-primary'");
      ?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li><?php echo html::a($actionLink, $lang->reviewcl->create);?></li>
        <li><?php echo html::a($this->createLink('reviewcl', 'batchCreate', "type=$type&object=$object"), $lang->reviewcl->batchCreate);?></li>
      </ul>
    </div>
    <?php else:?>
    <?php common::printLink('reviewcl', 'batchCreate', "type=$type&object=$object", "<i class='icon icon-plus'></i>" . $lang->reviewcl->batchCreate, '', "class='btn btn-primary'");?>
    <?php common::printLink('reviewcl', 'create',      "type=$type&object=$object", "<i class='icon icon-plus'></i>" . $lang->reviewcl->create,      '', "class='btn btn-primary'");?>
    <?php endif;?>
  </div>
  </div>
</div>
<div id="mainContent" class="main-row fade in">
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <ul class='tree'>
      <?php foreach($lang->baseline->objectList as $key => $value):?>
      <?php if(empty($key) or empty($value)) continue;?>
      <?php $active = $object == $key ? 'active' : '';?>
      <li><?php echo html::a($this->createLink('reviewcl', $method, "type={$type}&object={$key}"), $value, '', "class= $active");?></li>
      <?php endforeach;?>
      </ul>
    </div>
  </div>
  <div class="main-col">
    <div id="mainContent" class="main-table">
      <?php if(empty($reviewcls)):?>
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->reviewcl->noReviewcl;?></span></p>
      </div>
      <?php else:?>
      <table class='table has-sort-head table-fixrd' id="reviewclTable">
        <?php $vars = "type=$type&object=$object&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
          <thead>
            <tr>
              <th class='w-100px'><?php common::printOrderLink('category',    $orderBy, $vars, $lang->reviewcl->category);?></th>
              <th class='w-70px'> <?php common::printOrderLink('id',          $orderBy, $vars, $lang->reviewcl->id);?></th>
              <th>                <?php common::printOrderLink('title',       $orderBy, $vars, $lang->reviewcl->title);?></th>
              <th class='w-150px'><?php common::printOrderLink('object',      $orderBy, $vars, $lang->reviewcl->object);?></th>
              <th class='w-120px'><?php common::printOrderLink('createdBy',   $orderBy, $vars, $lang->reviewcl->createdBy);?></th>
              <th class='w-120px'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->reviewcl->createdDate);?></th>
              <th class='c-action w-80px'><?php echo $lang->reviewcl->actions;?></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($reviewcls as $categoryID => $values):?>
          <?php $firstRow = true;?>
          <?php foreach($reviewcls[$categoryID] as $reviewcl):?>
          <tr>
            <?php if($firstRow):?>
            <td rowspan=<?php echo count($reviewcls[$categoryID]);?>><?php echo zget($lang->reviewcl->{$type . 'CategoryList'}, $categoryID); ?></td>
            <?php endif;?>
            <td><?php echo $reviewcl->id;?></td>
            <td><?php echo html::a($this->createLink('reviewcl', 'view', "id=$reviewcl->id"), $reviewcl->title);?></td>
            <td><?php echo zget($lang->baseline->objectList, $reviewcl->object);?></td>
            <td><?php echo zget($users, $reviewcl->createdBy);?></td>
            <td><?php echo substr($reviewcl->createdDate, 0, 11);?></td>
            <td class='c-actions'>
            <?php
            $vars = "id={$reviewcl->id}";
            common::printIcon('reviewcl', 'edit',   $vars, $reviewcl, 'list');
            common::printIcon('reviewcl', 'delete', $vars, $reviewcl, 'list', 'trash', 'hiddenwin');
            ?>
          </tr>
          <?php $firstRow = false;?>
          <?php endforeach;?>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer table-statistic'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
