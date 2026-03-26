<table class='table table-form'>
  <thead>
    <tr class='text-center'>
      <?php
      foreach($fields as $field)
      {
          if(!$field->show) continue;

          $width    = ($field->width && $field->width != 'auto') ? $field->width . 'px' : 'auto';
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
        echo "<tr data-key='$row'>";

        $index = 1;
        foreach($fields as $field)
        {
            if(!$field->show) continue;

            echo '<td>';

            $value = $field->defaultValue ? $field->defaultValue : zget($data, $field->field, '');

            echo $this->flow->buildControl($field, $value, "dataList[$row][$field->field]");
            if($index == 1) echo "<div id='error{$row}'></div>";

            echo '</td>';

            $index++;
        }

        echo '</tr>';

        $row++;
    }
    ?>
  </tbody>
</table>
