<div id='prevModules-list'>
  <?php /* Begin foreach of prevModules. */ ?>
  <?php foreach($prevModules as $prevModule => $prevFields):?>
  <?php
  $display = true;
  if($mode == 'view')
  {
      $display = false;
      foreach($prevFields as $field)
      {
          if($field->show)
          {
              $display = true;
              break;
          }
      }
  }
  if(!$display) continue;
  ?>
  <div class='panel'>
    <table class='table table-layout prev prev-<?php echo $prevModule;?>' data-module="<?php echo $prevModule;?>">

      <?php /* Block Title */ ?>
      <?php $blockTitle = $lang->workflowrelation->prev . $lang->hyphen . zget($flowPairs, $prevModule);?>
      <tr class='head'>
        <td colspan='2'>
          <i class='icon-check'></i>
          <?php if($mode == 'edit'):?>
          <span class='title'><span class='title-bar'><strong><?php echo $blockTitle;?></strong></span></span>
          <?php else:?>
          <strong><?php echo $blockTitle;?></strong>
          <?php endif;?>
        </td>
      </tr>

      <?php /* Begin foreach of prevFields. */ ?>
      <?php foreach($prevFields as $key => $field):?>
      <?php if($mode == 'view' && !$field->show) continue;?>
      <?php $show = $field->show == '1';?>
      <tr class='<?php echo ($show ? '' : ' disabled');?>' data-key='<?php echo $key;?>'>
        <td>
          <i class='icon-check'></i>
          <?php /* Row title. */ ?>
          <span class='title'>
            <span class='title-bar' title='<?php echo $field->name;?>'>
              <strong><?php echo $field->name;?></strong>
              <?php if($mode == 'edit'):?>
              <i class='icon icon-move'></i>
              <?php endif;?>
            </span>
          </span>
        </td>

        <?php /* Row actions. */ ?>
        <td class='w-100px text-center'>
          <?php /* Display or not. */ ?>
          <?php if($mode == 'edit'):?>
          <button type='button' class='btn btn-link show-hide'>
            <span class='label-show'><?php echo $lang->workflowlayout->show;?></span>
            <span class='text-muted'>/</span>
            <span class='label-hide'><?php echo $lang->workflowlayout->hide;?></span>
          </button>
          <?php else:?>
          <?php echo $show ? $lang->workflowlayout->show : $lang->workflowlayout->hide;?>
          <?php endif;?>
          <?php echo html::hidden("prevModules[$prevModule][show][$key]",  $show ? '1' : '0');?>
        </td>
      </tr>
      <?php endforeach;?>
      <?php /* End foreach of prevFields. */ ?>
    </table>
  </div>
  <?php endforeach;?>
  <?php /* End foreach of prevModules. */ ?>
</div>
