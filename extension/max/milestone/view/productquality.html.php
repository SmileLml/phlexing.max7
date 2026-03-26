<table class="table table-bordered">
  <thead>
    <tr>
      <th rowspan='2'><?php echo $lang->milestone->quality->identify;?></th>
      <th class='text-center' colspan='<?php if(isset($productQuality['stages'])) echo count($productQuality['stages']);?>'>
      <?php echo $lang->milestone->quality->injection;?></th>
      <th rowspan='2'><?php echo $lang->milestone->quality->scale;?></th>
      <th rowspan='2'><?php echo $lang->milestone->quality->identifyRate;?></th>
    </tr>
    <tr>
      <?php if(isset($productQuality['stages'])):?>
      <?php foreach($productQuality['stages'] as $stages):?>
      <th title=<?php echo $stages['name'];?>><?php echo $stages['name'];?></th>
      <?php endforeach;?>
      <?php endif;?>
    </tr>
  </thead>
  <tbody>
    <?php if(isset($productQuality['reviews'])):?>
    <?php foreach($productQuality['reviews'] as $reviewID => $review):?>
    <tr>
      <td><?php echo $review['name'];?></td>
      <?php foreach($productQuality['stages'] as $stages):?>
      <td><?php echo $stages[$reviewID]['counts'];?></td>
      <?php endforeach;?>
      <td><?php echo $productQuality['reviews'][$reviewID]['reviewEstimate']; ?></td>
      <td><?php echo $productQuality['reviews'][$reviewID]['identifyRate']; ?></td>
    </tr>
    <?php endforeach;?>
    <?php endif;?>
    <tr>
      <th><?php echo $lang->milestone->quality->total;?></th>
      <?php if(isset($productQuality['stages'])):?>
      <?php foreach($productQuality['stages'] as $stages):?>
      <td><?php echo $stages['total'];?></td>
      <?php endforeach;?>
      <?php endif;?>
      <?php if(isset($productQuality['totalEstimate'])):?>
      <td><?php echo $productQuality['totalEstimate'];?></td>
      <?php endif;?>
      <?php if(isset($productQuality['totalIdentifyRate'])):?>
      <td><?php echo $productQuality['totalIdentifyRate'];?></td>
      <?php endif;?>
    </tr>
    <tr>
      <th><?php echo $lang->milestone->quality->injectionRate;?>
      </th>
      <?php if(isset($productQuality['stages'])):?>
      <?php foreach($productQuality['stages'] as $stages):?>
      <td><?php echo $stages['injection'];?></td>
      <?php endforeach;?>
      <?php endif;?>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table>
