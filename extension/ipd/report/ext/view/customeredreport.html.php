<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-row fade">
  <div class='side-col col-lg' id='sidebar'>
    <?php include './blockreportlist.html.php';?>
  </div>
  <div class="main-col">
    <div class="clearfix" id="mainMenu">
      <div class="container">
        <div class="btn-toolbar pull-left"> <?php echo $title;?></div>
        <div class="btn-toolbar pull-right">
           <?php echo html::a($this->createLink('measurement', 'viewTemplate', "id={$template->id}&projectID=$projectID", '', true), $this->lang->report->instanceNew, '', "class='btn btn-primary iframe'");?>
        </div>
      </div>
    </div>
    <div class="container">
      <?php if(empty($reports)):?>
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
      </div>
      <?php else:?>
      <div class='main-table'>
        <table class='table'>
          <thead>
            <tr>
              <th class='w-60px'><?php echo $lang->idAB;?></th>
              <th><?php echo $lang->measurement->report->name;?></th>
              <th class='w-120px'><?php echo $lang->measurement->report->createdBy;?></th>
              <th class='w-120px'><?php echo $lang->measurement->report->createdDate;?></th>
              <th class='c-actions'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($reports as $report):?>
            <tr>
              <td><?php echo $report->id;?></td>
              <td><?php echo $report->name;?></td>
              <td><?php echo $report->createdBy;?></td>
              <td><?php echo $report->createdDate;?></td>
              <td class='c-actions'>
                <?php echo common::printIcon('report', 'viewReport', "id=$report->id", $report, '', 'eye', '', '', false, '', $lang->report->customeredReport);?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <div class='table-footer'></div>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
