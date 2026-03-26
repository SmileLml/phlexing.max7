<table class='table bordered text-center'>
  <tr>
    <th><?php echo $lang->block->createdTodos;?></th>
    <?php if($config->URAndSR):?>
    <th><?php echo $lang->block->createdRequirements;?></th>
    <?php endif;?>
    <th><?php echo $lang->block->createdStories;?></th>
    <th><?php echo $lang->block->finishedTasks;?></th>
    <th><?php echo $lang->block->createdBugs;?></th>
    <th><?php echo $lang->block->resolvedBugs;?></th>
  </tr>
  <tr>
    <td><?php echo empty($data['createdTodos']) ? 0 : html::a($this->createLink('my', 'todo', 'type=all'), (int)$data['createdTodos']);?></td>
    <?php if($config->URAndSR):?>
    <td><?php echo empty($data['createdRequirements']) ? 0 : (int)$data['createdRequirements'];?></td>
    <?php endif;?>
    <td><?php echo empty($data['createdStories']) ? 0 : (int)$data['createdStories'];?></td>
    <td><?php echo empty($data['finishedTasks']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=task&type=finishedBy'), (int)$data['finishedTasks']);?></td>
    <td><?php echo empty($data['createdBugs']) ? 0 : (int)$data['createdBugs'];?></td>
    <td><?php echo empty($data['resolvedBugs']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=bug&type=resolvedBy'), (int)$data['resolvedBugs']);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->block->createdCases;?></th>
    <?php if(isset($config->maxVersion)):?>
    <th><?php echo $lang->block->createdRisks;?></th>
    <th><?php echo $lang->block->resolvedRisks;?></th>
    <th><?php echo $lang->block->createdIssues;?></th>
    <th><?php echo $lang->block->resolvedIssues;?></th>
    <?php endif;?>
    <th><?php echo $lang->block->createdDocs;?></th>
  </tr>
  <tr>
    <td><?php echo empty($data['createdCases']) ? 0 : (int)$data['createdCases'];?></td>
    <?php if(isset($config->maxVersion)):?>
    <td><?php echo empty($data['createdRisks']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=risk'), (int)$data['createdRisks']);?></td>
    <td><?php echo $data['resolvedRisks'];?></td>
    <td><?php echo empty($data['createdIssues']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=issue'), (int)$data['createdIssues']);?></td>
    <td><?php echo $data['resolvedIssues'];?></td>
    <?php endif;?>
    <td><?php echo $data['createdDocs'];?></td>
  </tr>
</table>
