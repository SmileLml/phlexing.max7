<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
      foreach($lang->demandpool->labelList as $label => $labelName)
      {
          $active = $browseType == $label ? 'btn-active-text' : '';
          echo html::a($this->createLink('demandpool', 'browse', "browseType=$label&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), '<span class="text">' . $labelName . '</span>' . ($browseType == $label ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active'");
      }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('demandpool', 'create')) echo html::a($this->createLink('demandpool', 'create'), "<i class='icon-plus'></i> {$lang->demandpool->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='demandpool' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=demandpool');?>"></div>
    <?php if(empty($demandpools)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->demandpool->noData;?></span>
        <?php if(common::hasPriv('demandpool', 'create')):?>
        <?php echo html::a($this->createLink('demandpool', 'create'), "<i class='icon icon-plus'></i> " . $lang->demandpool->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' method='post' id='demandpoolForm'>
      <?php $vars = "browseType=$browseType&param=0&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
      <table class='table has-sort-head' id='demandpoolList'>
        <thead>
          <tr>
            <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->demandpool->idAB);?></th>
            <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->demandpool->name);?></th>
            <th class='w-160px'><?php common::printOrderLink('owner',  $orderBy, $vars, $lang->demandpool->owner);?></th>
            <?php foreach($lang->demandpool->colList as $key => $label):?>
            <th class='c-estimate text-center c-<?php echo $key;?>'><?php echo $label;?></th>
            <?php endforeach;?>
            <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($demandpools as $demandpool):?>
          <tr>
            <td><?php echo sprintf('%03d', $demandpool->id);?></td>
            <td title="<?php echo $demandpool->name;?>" class='c-name'>
              <?php echo html::a($this->createLink('demand', 'browse', "demandpoolID=$demandpool->id"), $demandpool->name);?>
            </td>
            <?php
            $owner = '';
            if(!empty($demandpool->owner))
            {
                foreach(explode(',', str_replace(' ', '', $demandpool->owner)) as $account) $owner .= ' ' . zget($users, $account);
            }
            ?>
            <td class='c-name' title="<?php echo trim($owner);?>"><?php echo trim($owner);?></td>
            <?php foreach($lang->demandpool->colList as $key => $label):?>
            <td class='text-center'><?php echo isset($demandpool->summary[$key]) ? $demandpool->summary[$key]->count : 0;?></td>
            <?php endforeach;?>
            <td class='c-actions'>
              <?php
              common::printIcon('demandpool', 'edit', "demandpoolID=$demandpool->id", $demandpool, 'list');
              if($demandpool->status == 'normal') common::printIcon('demandpool', 'close', "demandpoolID=$demandpool->id", $demandpool, 'list', 'off', '', 'iframe', true);
              if($demandpool->status == 'closed') common::printIcon('demandpool', 'activate', "demandpoolID=$demandpool->id", $demandpool, 'list', '', '', 'iframe', true);
              common::printIcon('demandpool', 'delete', "demandpoolID=$demandpool->id", $demandpool, 'list', 'trash', 'hiddenwin');
              ?>
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
