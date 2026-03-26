<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datatable.fix.html.php';?>
<style>#tableCustomBtn+.dropdown-menu > li:last-child{display: none}</style>
<?php js::set('browseType', $browseType);?>
<?php js::set('pageSummary', $lang->review->pageSummary);?>
<?php js::set('pageAllSummary', $lang->review->pageAllSummary);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    foreach($lang->review->featureBar['browse'] as $type => $label)
    {
        $active = $type == $browseType ? 'btn-active-text' : '';
        echo html::a($this->createLink('review', 'browse', "project=$projectID&browseType=$type"), "<span class='text'>" . $label . '</span> ' . ($browseType == $type ? "<span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('review', 'create', "project=$projectID", "<i class='icon icon-plus'></i>" . $lang->review->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class='main-col'>
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=review');?>" data-module="review"></div>
    <?php if(empty($reviewList)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' method='post' id='reviewForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $vars = "project=$projectID&browseType=$browseType&orderBy=%s&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
      include $app->getModuleRoot() . 'common/view/datatable.html.php';

      $setting = $this->datatable->getOldSetting('review');
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
          <?php foreach($reviewList as $review):?>
          <tr data-id='<?php echo $review->id?>' data-status='<?php echo $review->status?>'>
            <?php foreach($setting as $value) $this->review->printCell($value, $review, $users, $products, $pendingReviews, $project, $reviewers);?>
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
    $('#reviewForm').table({
        statisticCreator: function(table)
        {
            var $table       = table.getTable();
            var $checkedRows = $table.find(table.isDataTable ? '.datatable-row-left.checked' : 'tbody>tr.checked');
            var $originTable = table.isDataTable ? table.$.find('.datatable-origin') : null;
            var checkedTotal = $checkedRows.length;
            var $rows        = checkedTotal ? $checkedRows : $table.find(table.isDataTable ? '.datatable-rows .datatable-row-left' : 'tbody>tr');

            var checkedWait      = 0;
            var checkedReviewing = 0;
            var checkedPass      = 0;
            var checkedAuditing  = 0;
            var checkedDone      = 0;
            $rows.each(function()
            {
                var $row = $(this);
                if($originTable) $row = $originTable.find('tbody>tr[data-id="' + $row.data('id') + '"]');

                var data = $row.data();

                if(data.status === 'wait')      checkedWait++;
                if(data.status === 'reviewing') checkedReviewing++;
                if(data.status === 'pass')      checkedPass++;
                if(data.status === 'auditing')  checkedAuditing++;
                if(data.status === 'done')      checkedDone++;
            });

            if(browseType == 'reviewing' || browseType == 'done') return pageSummary.replace('%s', $rows.length);
            return pageAllSummary.replace('%total%', $rows.length).replace('%wait%', checkedWait).replace('%reviewing%', checkedReviewing).replace('%pass%', checkedPass).replace('%auditing%', checkedAuditing).replace('%done%', checkedDone);
        }
    });
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
