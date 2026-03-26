<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>.strong-new-td{font-weight:bold;}</style>
<div id="mainMenu" class="clearfix">
</div>
<div id="mainContent" class="main-row fade in">
  <div class="main-col">
    <div class="main-table">
      <table class="table table-bordered has-sort-head table-fixed">
        <thead>
         <tr class="text-center">
           <td colspan="13"><h5><?php echo $lang->cm->baselineReport;?></h5></td>
         </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong><?php echo $lang->cm->projectID;?></strong></td>
            <td colspan="3"><?php echo $report['project']->id;?></td>
            <td><strong><?php echo $lang->cm->release;?></strong></td>
            <td colspan="4"><?php echo zget($lang->project->modelList, $report['project']->model);?></td>
            <td><strong><?php echo $lang->cm->approver;?></strong></td>
            <td colspan="3"></td>
          </tr>
          <tr>
            <td><strong><?php echo $lang->cm->projectName;?></strong></td>
            <td colspan="8"><?php echo $report['project']->name;?></td>
            <td><strong><?php echo $lang->cm->compiling;?></strong></td>
            <td colspan="3"><?php echo zget($users, $report['project']->PM);?></td>
          </tr>
          <tr class="text-center">
            <td colspan="13"><h5><?php echo $lang->cm->changeDesc;?></h5></td>
          </tr>
          <tr>
            <td colspan="2"><strong><?php echo $lang->cm->baselineItem;?></strong></td>
            <td colspan="2"><strong><?php echo $lang->cm->configItemName;?></strong></td>
            <td colspan="3"><strong><?php echo $lang->cm->configIdentify;?></strong></td>
            <td colspan="3"><strong><?php echo $lang->cm->currentVersion;?></strong></td>
            <td colspan="2"><strong><?php echo $lang->cm->releaseDate;?></strong></td>
            <td colspan="1"><strong><?php echo $lang->cm->publisher;?></strong></td>
          </tr>
          <?php $newCm = 0;?>
          <?php foreach($report['audit'] as $type => $item):?>
          <?php $rowspan = count($item);?>
          <?php foreach($item as $audit):?>
          <?php if($newCm < $audit->id) $newCm = $audit->id;?>
          <tr id="newCm<?php echo $audit->id;?>">
            <?php if($rowspan):?>
            <td colspan="2" rowspan="<?php echo $rowspan;?>"><?php echo $lang->cm->$type;?></td>
            <?php endif;?>
            <td colspan="2"><?php echo zget($lang->baseline->objectList, $audit->category);?></td>
            <td colspan="3"><?php echo $audit->title;?></td>
            <td colspan="3"><?php echo $audit->version;?></td>
            <td colspan="2"><?php echo $audit->createdDate;?></td>
            <td colspan="1"><?php echo zget($users, $audit->createdBy);?></td>
          </tr>
          <?php $rowspan = 0;?>
          <?php endforeach;?>
          <?php endforeach;?>
          <tr>
            <td colspan="8"><strong><?php echo $lang->cm->configAdmin;?></strong></td>
            <td colspan="4"><strong><?php echo $lang->cm->solutionMan;?></strong></td>
            <td><strong><?php echo $lang->cm->confManagement;?></strong></td>
          </tr>
          <tr>
            <td><strong><?php echo $lang->cm->issueID;?></strong></td>
            <td colspan="2"><strong><?php echo $lang->cm->issueDesc;?></strong></td>
            <td colspan="2"><strong><?php echo $lang->cm->baselineAudit;?></strong></td>
            <td><strong><?php echo $lang->cm->discoveryDate;?></strong></td>
            <td><strong><?php echo $lang->cm->personLiable;?></strong></td>
            <td><strong><?php echo $lang->cm->proposedScheme;?></strong></td>
            <td><strong><?php echo $lang->cm->proposedDate;?></strong></td>
            <td><strong><?php echo $lang->cm->solutionResult;?></strong></td>
            <td><strong><?php echo $lang->cm->resolvingDate;?></strong></td>
            <td><strong><?php echo $lang->cm->resolvingBy;?></strong></td>
            <td><strong><?php echo $lang->cm->currentState;?></strong></td>
          </tr>
          <?php foreach($report['issue'] as $issue):?>
          <tr>
            <td><?php echo $issue->id;?></td>
            <td colspan="2"><?php echo $issue->title;?></td>
            <td colspan="2"><?php echo $issue->reviewTitle;?></td>
            <td><?php echo $issue->createdDate;?></td>
            <td><?php echo zget($users, $issue->createdBy);?></td>
            <td><?php echo $issue->opinion;?></td>
            <td><?php echo $issue->opinionDate;?></td>
            <td><?php echo zget($lang->reviewissue->resolutionList, $issue->resolution);?></td>
            <td><?php echo $issue->resolutionDate;?></td>
            <td><?php echo zget($users, $issue->resolutionBy);?></td>
            <td><?php echo zget($lang->reviewissue->statusList, $issue->status);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php js::set('newCm', $newCm);?>
<script>
$("#newCm" + newCm).addClass("strong-new-td");
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
