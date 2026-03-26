<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datatable.fix.html.php';?>
<style>.c-auditplan {overflow: hidden; text-overflow: ellipsis;}</style>
<?php js::set('pageSummary', $lang->nc->pageSummary);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    foreach($lang->nc->featureBar['browse'] as $type => $label)
    {
        $active = $type == $browseType ? 'btn-active-text' : '';
        echo html::a($this->createLink('nc', 'browse', "project=$projectID&from=$from&browseType=$type"), "<span class='text'>" . $label . '</span> ' . ($browseType == $type ? "<span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active' data-app='{$app->tab}'");
    }
    $rawModule = $this->app->rawModule;
    $rawMethod = $this->app->rawMethod;
    foreach(customModel::getFeatureMenu($rawModule, $rawMethod) as $menuItem)
    {
        if(isset($menuItem->hidden)) continue;
        $menuType = $menuItem->name;
        if($menuType == 'QUERY')
        {
            $searchBrowseLink = $this->createLink('nc', 'browse', "project=$projectID&from=$from&browseType=bySearch&param=%s");
            $isBySearch       = $browseType == 'bysearch';
            include $app->getModuleRoot() . 'common/view/querymenu.html.php';
        }
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group'>
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export ?></span> <span class="caret"></span></button>
      <ul class='dropdown-menu pull-right' id='exportActionMenu'>
        <?php
        $class = common::hasPriv('nc', 'export') ? "" : "class='disabled'";
        $misc  = common::hasPriv('nc', 'export') ? "class='export'" : "class='disabled'";
        $link  = common::hasPriv('nc', 'export') ? $this->createLink('nc', 'export', "projectID={$projectID}&from={$from}&browseType={$browseType}&orderBy={$orderBy}") : '#';
        echo "<li $class>" . html::a($link, $lang->nc->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php common::canModify($from, $project) ? common::printLink('nc', 'create', "project=$projectID&from=$from", "<i class='icon icon-plus'></i>" . $lang->nc->create, '', "class='btn btn-primary' data-app='{$app->tab}'", '') : null;?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class='main-col'>
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=nc');?>" data-module='nc'></div>
    <?php if(empty($ncs)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' method='post' id='ncForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $vars = "project=$projectID&from=$from&browseType=$browseType&param=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
      include $app->getModuleRoot() . 'common/view/datatable.html.php';

      $setting = $this->datatable->getOldSetting('nc');
      $widths  = $this->datatable->setFixedFieldWidth($setting);
      ?>
        <table class='table has-sort-head datatable' id='bugList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
          <thead>
            <tr>
              <?php
              foreach($setting as $value)
              {
                  if($value->show)
                  {
                      $this->datatable->printHead($value, $orderBy, $vars, false);
                  }
              }
              ?>
            </tr>
          </thead>
          <tbody>
          <?php foreach($ncs as $nc):?>
          <tr data-id='<?php echo $nc->id?>' data-status='<?php echo $nc->status?>'>
            <?php foreach($setting as $value) $this->nc->printCell($value, $nc, $users, $activities, $outputs, $projectID, $from);?>
          </tr>
          <?php endforeach;?>
          </tbody>
        </table>
      <div class='table-footer'>
        <div class="table-statistic"></div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>
<script>
$(function()
{
    $('#ncForm').table({
        statisticCreator: function(table)
        {
            var $table       = table.getTable();
            var $checkedRows = $table.find(table.isDataTable ? '.datatable-row-left.checked' : 'tbody>tr.checked');
            var $originTable = table.isDataTable ? table.$.find('.datatable-origin') : null;
            var checkedTotal = $checkedRows.length;
            var $rows        = checkedTotal ? $checkedRows : $table.find(table.isDataTable ? '.datatable-rows .datatable-row-left' : 'tbody>tr');

            var checkedActive   = 0;
            $rows.each(function()
            {
                var $row = $(this);
                if($originTable) $row = $originTable.find('tbody>tr[data-id="' + $row.data('id') + '"]');

                var data = $row.data();

                if(data.status === 'active')   checkedActive++;
            });

            return pageSummary.replace('%total%', $rows.length).replace('%active%', checkedActive);
        }
    });
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
