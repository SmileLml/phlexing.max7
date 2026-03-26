<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $browseLink = $this->session->ncList ? $this->session->ncList : $this->createLink('nc', 'browse', "program=$nc->project" . "&from=$from");?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary' data-app='{$app->tab}'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $nc->id?></span>
      <span class="text"><?php echo $nc->title;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class='cell'>
      <div class='detail-title'><?php echo $lang->nc->desc;?></div>
      <div class='detail-content'>
        <?php echo $nc->desc;?>
      </div>
    </div>
    <div class='cell'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$nc->deleted):?>
        <?php
        common::printIcon('nc', 'assignTo', "ncID=$nc->id", $nc, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('nc', 'resolve',  "ncID=$nc->id", $nc, 'button', 'checked', '', 'iframe showinonlybody', true);
        common::printIcon('nc', 'close',    "ncID=$nc->id", $nc, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('nc', 'activate', "ncID=$nc->id", $nc, 'button', '', '', 'iframe showinonlybody', true);
        echo "<div class='divider'></div>";
        common::printIcon('nc', 'edit', "ncID=$nc->id&from=$from&projectID=$projectID", $nc);
        common::printIcon('nc', 'delete', "ncID=$nc->id&from=$from", $nc, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class='cell'>
      <div class='detail-title'><?php echo $lang->nc->basicInfo;?></div>
      <div class='detail-content'>
        <table class='table-data'>
          <tr class='c-severity'>
            <th class='w-100px'><?php echo $lang->nc->severity;?></th>
            <td class='severity-issue severity-<?php echo $nc->severity;?>'><?php echo zget($lang->nc->severityList, $nc->severity);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->type;?></th>
            <td><?php echo zget($lang->nc->typeList, $nc->type);?></td>
          </tr>
          <tr <?php if(empty($project->multiple)) echo "class='hidden'";?>>
            <th class='w-90px'><?php echo $lang->nc->execution;?></th>
            <td><?php if(!empty($execution) && !common::printLink('execution', 'view', "executionID=$execution->id", $execution->name, '', 'data-app=execution')) echo $execution->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->object;?></th>
            <td><?php echo $nc->objectType == 'activity' ? zget($activities, $nc->objectID) : zget($outputs, $nc->objectID);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->status;?></th>
            <td class='status-<?php echo $nc->status;?>'><?php echo zget($lang->nc->statusList, $nc->status);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->deadline;?></th>
            <td><?php echo helper::isZeroDate($nc->deadline) ? '' : $nc->deadline;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->createdBy;?></th>
            <td><?php echo zget($users, $nc->createdBy);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->createdDate;?></th>
            <td><?php echo helper::isZeroDate($nc->createdDate) ? '' : $nc->createdDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->resolution;?></th>
            <td><?php echo zget($lang->nc->resolutionList, $nc->resolution);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->resolvedBy;?></th>
            <td><?php echo zget($users, $nc->resolvedBy);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->resolvedDate;?></th>
            <td><?php echo helper::isZeroDate($nc->resolvedDate) ? '' : $nc->resolvedDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->closedBy;?></th>
            <td><?php echo zget($users, $nc->closedBy);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->nc->closedDate;?></th>
            <td><?php echo helper::isZeroDate($nc->closedDate) ? '' : $nc->closedDate;?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext, $this->createLink('nc', 'view', "ncId=%s&from=$from&projectID=$projectID"));?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
