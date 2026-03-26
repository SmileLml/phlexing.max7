<?php
/**
 * The batch operate view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow 
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<style>
.preview-content .required:after {right: auto;}
.preview-content tbody .required:after {visibility: hidden;}
.preview-content .btn + .btn {margin-left: 5px;}
</style>
<div class='preview-content'>
  <table class='table table-borderless'>
    <thead>
      <tr class='text-center'>
        <?php
        foreach($fields as $field)
        {
            if(!$field->show) continue;

            $width    = $field->width == 'auto' ? '' : 'w-' . $field->width . 'px';
            $required = strpos(",$field->rules,", ",$notEmptyRule->id,") !== false ? 'required' : '';
            echo "<th class='$width $required'>$field->name</th>";
        }
        ?>
        <th class='w-100px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $row = 1;
      for($i = 0; $i < 3; $i++)
      {
          echo "<tr data-key='$row'>";

          $index = 1;
          foreach($fields as $field)
          {
              if(!$field->show) continue;

              $value = $field->defaultValue;

              if($field->control == 'select')
              {
                  if($row == 1)
                  {
                      $field->tmpOptions = $field->options;
                      unset($field->options['ditto']);
                  }
                  if($row > 1)
                  {
                      $field->options = $field->tmpOptions;
                      $value = 'ditto';
                  }
              }

              echo '<td>';

              $control = $this->flow->buildControl($field, $value, "preview_dataList[$row][$field->field]", '', false, true);
              $control = str_replace("rows='3'", "rows='1'", $control);

              echo $control;
              echo '</td>';

              $index++;
          }

          echo '<td>'; 
          echo "<a href='javascript:;' class='btn addItem'><i class='icon icon-plus'></i></a>";
          echo "<a href='javascript:;' class='btn delItem'><i class='icon icon-close'></i></a>";
          echo '</td>';
          echo '</tr>';

          $row++;
      }
      ?>
    </tbody>
  </table>
  <div class='form-actions text-center'>
    <?php echo html::submitButton();?>
    <?php echo baseHTML::commonButton($lang->goback);?>
  </div>
</div>
<script>
$('.preview-content a').attr('href', 'javascript:;').removeAttr('onclick');
$('.preview-content .form-actions a').attr('class', 'btn');
</script>
