<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
.scroll-table {overflow-x: scroll}
.scroll-table > .table {min-width:100%}
.stageName {font-weight: 400 !important;}
.reportType {text-align: left !important; font-weight: 700 !important;}
</style>
<?php if(common::checkNotCN()):?>
<style>#conditions .col-xs { width: 126px; }</style>
<?php endif;?>
<div id='mainContent' class='main-row'>
  <div class='side-col col-lg' id='sidebar'>
    <?php include './blockreportlist.html.php';?>
  </div>
  <div class='main-col'>
    <?php if(empty($stages)):?>
    <div class="cell">
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->error->noData;?></span></p>
      </div>
    </div>
    <?php else:?>
    <div class='cell'>
      <div class='panel'>
        <div class="panel-heading">
          <div class="panel-title">
            <div class="table-row" id='conditions'>
              <div class="col-xs"><?php echo $title;?></div>
            </div>
          </div>
          <nav class="panel-actions btn-toolbar"></nav>
        </div>
        <div data-ride='table' class='scroll-table'>
          <table class='table table-condensed table-striped table-bordered table-fixed no-margin' id='programList'>
            <thead>
              <tr>
                <th class='w-200px'><?php echo $lang->execution->name;?></th>
                <?php foreach($stages as $stage):?>
                <th class='stageName'><?php echo $stage->name;?></th>
                <?php endforeach;?>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class='reportType'><?php echo $lang->report->titleList['staff'];?></td>
                <?php foreach($stages as $stage):?>
                <?php $staff = zget($staffList, $stage->id, '');?>
                <td><?php echo $staff ? $staff->count : '';?></td>
                <?php endforeach;?>
             </tr>
             <tr>
                <td class='reportType'><?php echo $lang->report->titleList['pv'];?></td>
                <?php foreach($stages as $stage):?>
                <?php $pv = zget($pvList, $stage->id, '');?>
                <td><?php echo $pv;?></td>
                <?php endforeach;?>
             </tr>
             <tr>
                <td class='reportType'><?php echo $lang->report->titleList['ev'];?></td>
                <?php foreach($stages as $stage):?>
                <?php $ev = zget($evList, $stage->id, '');?>
                <td><?php echo $ev;?></td>
                <?php endforeach;?>
             </tr>
             <tr>
                <td class='reportType'><?php echo $lang->report->titleList['dv'];?></td>
                <?php foreach($stages as $stage):?>
                <?php $pv = zget($pvList, $stage->id, 0);?>
                <?php $ev = zget($evList, $stage->id, 0);?>
                <td><?php echo $ev - $pv;?></td>
                <?php endforeach;?>
             </tr>
             <tr>
                <td class='reportType'><?php echo $lang->report->titleList['dvrate'];?></td>
                <?php foreach($stages as $stage):?>
                <?php $pv = zget($pvList, $stage->id, 0);?>
                <?php $ev = zget($evList, $stage->id, 0);?>
                <td><?php echo $pv == 0 ? 0 : number_format(($ev - $pv) / $pv * 100, 2);?>%</td>
                <?php endforeach;?>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
