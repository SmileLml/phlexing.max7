<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
.scroll-table {overflow-x: scroll}
.scroll-table > .table {min-width:100%}
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
                <th class='w-200px'><?php echo $lang->project->name;?></th>
                <?php foreach($stages as $stage):?>
                <th><?php echo $stage->name;?></th>
                <?php endforeach;?>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class='text-left'><?php echo $lang->report->projectWorkload->titleList['staff'];?></td>
                <?php foreach($stages as $stage):?>
                <?php $staff = zget($staffList, $stage->id, '');?>
                <td><?php echo $staff ? $staff->count : '';?></td>
                <?php endforeach;?>
             </tr>
             <tr>
                <td class='text-left'><?php echo $lang->report->projectWorkload->titleList['pv'];?></td>
                <?php foreach($stages as $stage):?>
                <?php $pv = zget($pvList, $stage->id, '');?>
                <td><?php echo $pv;?></td>
                <?php endforeach;?>
             </tr>
             <tr>
                <td class='text-left'><?php echo $lang->report->projectWorkload->titleList['ev'];?></td>
                <?php foreach($stages as $stage):?>
                <?php $ev = zget($evList, $stage->id, '');?>
                <td><?php echo $ev;?></td>
                <?php endforeach;?>
             </tr>
             <tr>
                <td class='text-left'><?php echo $lang->report->projectWorkload->titleList['dv'];?></td>
                <?php foreach($stages as $stage):?>
                <?php $pv = zget($pvList, $stage->id, '');?>
                <?php $ev = zget($evList, $stage->id, '');?>
                <td><?php echo $ev - $pv;?></td>
                <?php endforeach;?>
             </tr>
             <tr>
                <td class='text-left'><?php echo $lang->report->projectWorkload->titleList['dvrate'];?></td>
                <?php foreach($stages as $stage):?>
                <?php $pv = zget($pvList, $stage->id, '');?>
                <?php $ev = zget($evList, $stage->id, '');?>
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
