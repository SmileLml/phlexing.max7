<?php js::import($jsRoot . 'math.js');?>
<div id='expressionDIV' class='hide'>
  <div class='expression'>
    <span class='item-name'><?php echo $lang->workflowfield->formula->common;?></span>
    <span>=</span>
  </div>
  <div class='clear-expression'>
    <?php echo baseHTML::a('javascript:;', $lang->workflowfield->formula->clearLast, "class='clear-last'");?>
    <?php echo baseHTML::a('javascript:;', $lang->workflowfield->formula->clearAll, "class='clear-all'");?>
  </div>
  <div class='detail'>
    <div class='detail-heading'><?php echo $lang->workflowfield->formula->target;?></div>
    <div class='detail-content'>
      <?php
      echo "<div module='$flow->module'>";
      $numberFields = $this->workflowfield->getNumberFields($flow->module);
      $modules      = array($flow->module => $flow->name);
      $moduleFields = array($flow->module => $numberFields);

      foreach($numberFields as $fieldCode => $target)
      {
          if(!empty($field->field) && $field->field == $fieldCode) continue;

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

              $modules[$subModule]      = $tableName;
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
      ?>
    </div>
  </div>
  <div class='detail'>
    <div class='detail-heading'><?php echo $lang->workflowfield->formula->operator;?></div>
      <?php foreach($config->workflowfield->formula->operators as $operator => $label) echo "<a href='javascript:;' data-type='operator' data-operator='$operator' data-text='$label' class='btn btn-expression'>$label</a>";?>
    <div class='detail-content'>
    </div>
  </div>
  <div class='detail'>
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
<?php js::set('modules', $modules);?>
<?php js::set('moduleFields', $moduleFields);?>
