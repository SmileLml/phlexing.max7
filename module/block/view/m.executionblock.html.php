<table class='table bordered'>
  <thead>
    <tr>
      <th><?php echo $title;?></th>
      <th class='text-center w-70px'><?php echo $lang->statusAB;?></th>
      <th class='text-center w-70px'><?php echo $lang->execution->progress;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($executionStats as $execution):?>
    <tr class= 'text-center' data-id='<?php echo $execution->id ?>' data-url='<?php echo $this->createLink('execution', 'task', 'executionID=' . $execution->id);?>'>
      <td class='text-left'><?php echo $execution->name;?></td>
      <?php if(isset($execution->delay)):?>
      <td><?php echo $lang->execution->delayed;?></td>
      <?php else:?>
      <td><?php echo $lang->execution->statusList[$execution->status];?></td>
      <?php endif;?>
      <td><?php echo $execution->hours->progress;?>%</td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
