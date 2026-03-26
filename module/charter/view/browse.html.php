<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datatable.fix.html.php';?>
<?php js::set('browseType', $browseType);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('charter', 'create')) echo html::a($this->createLink('charter', 'create'), "<i class='icon-plus'></i> {$lang->charter->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='charter' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=charter');?>"></div>
    <?php if(empty($charters)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->charter->noData;?></span>
        <?php if(common::hasPriv('charter', 'create')):?>
        <?php echo html::a($this->createLink('charter', 'create'), "<i class='icon icon-plus'></i> " . $lang->charter->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' method='post' id='charterForm'>
      <?php $vars = "browseType=$browseType&param=0&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
      <table class='table has-sort-head' id='charterList'>
        <thead>
          <tr>
            <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->charter->id);?></th>
            <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->charter->name);?></th>
            <th class='c-level'><?php echo $lang->charter->level;?></th>
            <th class='c-status'><?php echo $lang->charter->status;?></th>
            <th class='c-category'><?php echo $lang->charter->category;?></th>
            <th class='c-market'><?php echo $lang->charter->market;?></th>
            <th class='c-budget'><?php echo $lang->charter->budget;?></th>
            <th class='c-appliedBy'><?php echo $lang->charter->appliedBy;?></th>
            <th class='c-createdDate'><?php echo $lang->charter->createdDate;?></th>
            <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($charters as $charter):?>
          <tr>
            <td><?php echo sprintf('%03d', $charter->id);?></td>
            <td class='c-name' title="<?php echo $charter->name;?>">
              <?php echo html::a($this->createLink('charter', 'view', "charterID=$charter->id"), $charter->name);?>
            </td>
            <td><?php echo zget($lang->charter->levelList, $charter->level);?></td>
            <td><?php echo zget($lang->charter->statusList, $charter->status);?></td>
            <td><?php echo zget($lang->charter->categoryList, $charter->category);?></td>
            <td><?php echo zget($lang->charter->marketList, $charter->market);?></td>
            <td><?php echo $charter->budget;?></td>
            <td><?php echo zget($users, $charter->appliedBy);?></td>
            <td><?php echo $charter->createdDate;?></td>
            <td class='c-actions'>
              <?php echo $this->charter->buildOperateMenu($charter, 'browse');?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class="table-footer">
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
