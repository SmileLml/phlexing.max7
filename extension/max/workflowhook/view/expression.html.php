<?php js::import($jsRoot . 'math.js');?>
<?php js::set('expression', array());?>
<style>
#expressionDIV .detail {margin-bottom: 10px;}
#expressionDIV .detail-heading {margin-bottom: 10px;}
#expressionDIV .expression {border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; min-height: 100px;}
#expressionDIV .expression .item-expression {padding: 2px; margin: 3px; display: inline-block;}
#expressionDIV .expression .item-target {border: 1px solid #ddd; border-radius: 4px;}
#expressionDIV .expression .item-number {padding-right: 0px; margin-right: 0px;}
#expressionDIV .expression .item-number + .item-number {padding-left: 0px; margin-left: 0px;}
#expressionDIV .clear-expression {float: right; position: relative; top: -35px; right: 10px;}
#expressionDIV .clear-expression .clear-last {margin-right: 10px;}
#expressionDIV .btn-expression {min-width: 40px; padding-top: 10px; padding-bottom: 10px; margin-right: 5px; margin-bottom: 5px;}
</style>
<div id='expressionDIV' class='hide'>
  <div class='expression'>
    <span class='item-name'><?php echo $lang->workflowfield->formula->common;?></span>
    <span>=</span>
  </div>
  <div class='clear-expression'>
    <?php echo baseHTML::a('javascript:;', $lang->workflowfield->formula->clearLast, "class='clear-last'");?>
    <?php echo baseHTML::a('javascript:;', $lang->workflowfield->formula->clearAll, "class='clear-all'");?>
  </div>
  <div class='detail target-detail'>
    <div class='detail-heading'><?php echo $lang->workflowfield->formula->target;?></div>
    <div class='detail-content'>
      <?php
      $numberFields = $this->workflowfield->getNumberFields($flow->module, true);
      $moduleFields = array($flow->module => $numberFields);

      echo "<div module='$flow->module'>";
      foreach($numberFields as $fieldCode => $target)
      {
          $target = $flow->name . '_' . $target;
          echo "<a href='javascript:;' data-type='target' data-module='$flow->module' data-field='$fieldCode' data-text='$target' class='btn btn-expression'>$target</a>";
      }
      echo '</div>';

      if(!$flow->parent)
      {
          $subTables = $this->loadModel('workflow', 'flow')->getPairs($flow->module);
          foreach($subTables as $subModule => $tableName)
          {
              $subFields = $this->workflowfield->getNumberFields($subModule, true);

              $moduleFields[$subModule] = $subFields;

              echo "<div module='$subModule'>";
              foreach($subFields as $fieldCode => $fieldName)
              {
                  foreach($lang->workflowfield->formula->functions as $function => $label)
                  {
                      $target = sprintf($label, $tableName, $fieldName);
                      echo "<a href='javascript:;' data-type='target' data-module='$subModule' data-field='$fieldCode' data-function='$function' data-text='$target' class='btn btn-expression'>$target</a>";
                  }
              }
              echo '</div>';
          }
      }

      if(!empty($hook->table) && !isset($moduleFields[$hook->table])) $moduleFields[$hook->table] = $this->workflowfield->getNumberFields($hook->table, true);
      ?>
    </div>
  </div>
  <div class='detail operator-detail'>
    <div class='detail-heading'><?php echo $lang->workflowfield->formula->operator;?></div>
      <?php foreach($config->workflowfield->formula->operators as $operator => $label) echo "<a href='javascript:;' data-type='operator' data-operator='$operator' data-text='$label' class='btn btn-expression'>$label</a>";?>
    <div class='detail-content'>
    </div>
  </div>
  <div class='detail number-detail'>
    <div class='detail-heading'><?php echo $lang->workflowfield->formula->numbers;?></div>
    <div class='detail-content'>
      <?php foreach($config->workflowfield->formula->numbers as $number) echo "<a href='javascript:;' data-type='number' data-value='$number' data-text='$number' class='btn btn-expression'>$number</a>";?>
    </div>
  </div>
  <div class='form-actions text-center'>
    <?php echo baseHTML::a('javascript:;', $lang->save, "class='btn btn-primary save-expression'");?>
    <?php echo baseHTML::a('javascript:;', $lang->cancel, "class='btn cancel-expression'");?>
  </div>
</div>
<?php js::set('modules', $tables);?>
<?php js::set('moduleFields', $moduleFields);?>
