<table class='table table-form'>
  <thead>
    <tr class='text-center'>
      <?php
      foreach($fields as $field)
      {
          if(!$field->show) continue;

          $width    = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');
          $required = strpos(",$field->rules,", ",$notEmptyRule->id,") !== false ? 'required' : '';
          echo "<th class='$required' style='width: $width'>$field->name</th>";
      }
      ?>
    </tr>
  </thead>
  <tbody>
    <?php
    $row = 1;
    foreach($dataList as $data)
    {
        echo '<tr>';

        $index = 1;
        foreach($fields as $field)
        {
            if(!$field->show) continue;

            $value = ($field->defaultValue or $field->defaultValue === 0) ? $field->defaultValue : zget($data, $field->field, '');
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
                }
            }

            echo '<td>';

            $element = "dataList[$data->id][$field->field]";
            $control = $this->flow->buildControl($field, $value, $element);
            $control = str_replace("rows='3'", "rows='1'", $control);

            echo $control;
            if($index == 1) echo "<div id='error{$data->id}'></div>";

            echo '</td>';

            $index++;
        }

        echo '</tr>';

        $row++;
    }
    ?>
  </tbody>
</table>
<div class='form-actions text-center'>
  <?php echo html::submitButton();?>
  <?php echo html::backButton();?>
</div>
