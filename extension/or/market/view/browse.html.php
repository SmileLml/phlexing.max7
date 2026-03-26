<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
      foreach($lang->market->labelList as $label => $labelName)
      {
          $active = $browseType == $label ? 'btn-active-text' : '';
          echo html::a($this->createLink('market', 'browse', "browseType=$label&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), '<span class="text">' . $labelName . '</span>' . ($browseType == $label ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active'");
      }
    ?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('market', 'create')) echo html::a($this->createLink('market', 'create'), "<i class='icon-plus'></i> {$lang->market->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='market'></div>
    <?php if(empty($markets)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
        <?php if(common::hasPriv('market', 'create')):?>
        <?php echo html::a($this->createLink('market', 'create'), "<i class='icon icon-plus'></i> " . $lang->market->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' method='post' id='marketForm'>
      <?php $vars = "browseType=$browseType&param=0&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
      <table class='table has-sort-head' id='marketList'>
        <thead>
          <tr>
            <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
            <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->market->name);?></th>
            <th class='w-160px'><?php common::printOrderLink('industry',  $orderBy, $vars, $lang->market->industry);?></th>
            <th class='w-110px'><?php common::printOrderLink('scale',  $orderBy, $vars, $lang->market->scale);?></th>
            <th class='w-80px'><?php common::printOrderLink('maturity',  $orderBy, $vars, $lang->market->maturity);?></th>
            <th class='w-90px'><?php common::printOrderLink('speed',  $orderBy, $vars, $lang->market->speed);?></th>
            <th class='w-80px'><?php common::printOrderLink('competition',  $orderBy, $vars, $lang->market->competition);?></th>
            <th class='w-90px'><?php common::printOrderLink('ppm',  $orderBy, $vars, $lang->market->ppm);?></th>
            <th class='w-80px'><?php common::printOrderLink('strategy',  $orderBy, $vars, $lang->market->strategy);?></th>
            <th class='w-120px'><?php common::printOrderLink('openedDate',  $orderBy, $vars, $lang->market->openedDate);?></th>
            <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($markets as $market):?>
          <tr>
            <td><?php echo sprintf('%03d', $market->id);?></td>
            <td class='c-name' title="<?php echo $market->name;?>">
              <?php echo common::hasPriv('market', 'view') ? html::a($this->createLink('market', 'view', "marketID=$market->id"), $market->name) : $market->name;?>
            </td>
            <td class='c-industry' title="<?php echo $market->industry;?>"><?php echo $market->industry;?></td>
            <td class='c-scale' title="<?php echo $market->scale != 0 ? $market->scale . " " . $lang->market->hundredMillion : '';?>"><?php echo $market->scale != 0 ? $market->scale . " " . $lang->market->hundredMillion : '';?></td>
            <td title="<?php echo zget($lang->market->maturityList, $market->maturity);?>">              <?php echo zget($lang->market->maturityList, $market->maturity);?></td>
            <td title="<?php echo zget($lang->market->speedList, $market->speed);?>">                    <?php echo zget($lang->market->speedList, $market->speed);?></td>
            <td title="<?php echo zget($lang->market->competitionList, $market->competition);?>">        <?php echo zget($lang->market->competitionList, $market->competition);?></td>
            <td title="<?php echo zget($lang->market->ppmList, $market->ppm);?>"><?php echo zget($lang->market->ppmList, $market->ppm);?></td>
            <td title="<?php echo zget($lang->market->strategyList, $market->strategy);?>">              <?php echo zget($lang->market->strategyList, $market->strategy);?></td>
            <td><?php echo helper::isZeroDate($market->openedDate) ? '' : substr($market->openedDate, 5, 11);?></td>
            <td class='c-actions'>
              <?php echo $this->market->buildOperateBrowseMenu($market);?>
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
