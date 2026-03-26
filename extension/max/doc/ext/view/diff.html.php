<div id='mainContent' class='main-content'>
  <table class='table table-data alldiff'>
    <?php foreach($diff as $field => $renderedDiff):?>
    <?php
    $oldIsImage = $this->doc->isImage($oldDoc->$field);
    $newIsImage = $this->doc->isImage($newDoc->$field);
    ?>
    <?php if($oldIsImage and $newIsImage):?>
    <?php if($renderedDiff == 'same') continue;?>
    <tr>
      <th class='w-80px'><?php echo $lang->doc->$field?></th>
      <?php if($renderedDiff === false):?>
      <td>
      <img onload="setImageSize(this,0)" src="<?php echo $oldIsImage?>" />
      </td>
      <td>
      <img onload="setImageSize(this,0)" src="<?php echo $newIsImage?>" />
      </td>
      <?php else:?>
      <td colspan='2'>
      <img onload="setImageSize(this,0)" src="data:image/png;base64,<?php echo base64_encode(file_get_contents($renderedDiff))?>" />
      </td>
      <?php endif;?>
    </tr>
    <?php else:?>
    <?php if(empty($renderedDiff)) continue;?>
    <?php if(strpos($renderedDiff, '<ins>') === false and strpos($renderedDiff, '<del>') === false) continue;?>
    <tr>
      <td>
        <div class="htmldiff"><?php echo $renderedDiff?></div>
      </td>
    </tr>
    <?php endif;?>
    <?php endforeach;?>
  </table>
</div>
