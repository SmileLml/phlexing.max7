<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php if(common::checkNotCN()):?>
<style>#conditions .col-xs {width: 126px;}</style>
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
        <div data-ride='table'>
          <table class='table table-condensed table-striped table-bordered table-fixed no-margin' id='programList'>
            <thead>
              <tr>
                <th class='w-200px'><?php echo $lang->execution->name;?></th>
                <th class='w-120px text-center'><?php echo $lang->execution->owner;?></th>
                <th class="w-100px text-center"><?php echo $lang->execution->begin;?></th>
                <th class="w-100px text-center"><?php echo $lang->execution->end;?></th>
                <th class="w-70px  text-center"><?php echo $lang->execution->taskCount;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($stages as $stage):?>
              <tr class="text-center">
                <td class='text-left' title="<?php echo $stage->name;?>"><?php echo $stage->name;?></td>
                <td><?php echo zget($users, $stage->PM);?></td>
                <td><?php echo $stage->begin;?></td>
                <td><?php echo $stage->end;?></td>
                <td><?php echo $stage->tasks;?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
