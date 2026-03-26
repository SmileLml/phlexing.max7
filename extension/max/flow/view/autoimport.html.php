<style>
#importTable thead tr th {min-width: 120px;}
</style>
<table class='table table-bordered' id='importTable'>
  <thead>
    <tr class='text-center'>
      <?php
      $rowspan = empty($titles['sub_tables']) ? '' : "rowspan='2'";
      foreach($titles as $key => $field)
      {
          if($key == 'sub_tables')
          {
              foreach($field as $subTable)
              {
                  $colspan = empty($subTable['fields']) ? '' : 'colspan=' . count($subTable['fields']);
                  echo "<th {$colspan}'>{$subTable['name']}</th>";
              }
          }
          else
          {
              $required = strpos(",{$fields[$flow->module][$key]->rules},", ",$notEmptyRule->id,") !== false ? 'required' : ''; 
              echo "<th class='$required' {$rowspan}>{$field}</th>";
          }
      }
      ?>
    </tr>
    <?php if(!empty($titles['sub_tables'])):?>
    <tr>
      <?php
      foreach($titles['sub_tables'] as $subTable)
      {
          if(empty($subTable['fields'])) continue;
          foreach($subTable['fields'] as $field) echo "<th>{$field}</th>";
      }
      ?>
    </tr>
    <?php endif;?>
  </thead>
  <tbody>
    <?php
    $row = 1;
    foreach($dataList as $key => $data)
    {
        $keys = array($key => $key);

        $subDataCount = 0;

        if(isset($data['sub_tables']))
        {
            foreach($data['sub_tables'] as $subModule => $subDatas)
            {
                foreach($subDatas as $subKey => $subData) $keys[$subKey] = $subKey;

                if(count($subDatas) > $subDataCount) $subDataCount = count($subDatas);
            }
        }

        $subRow  = 1;
        $rowspan = $subDataCount ? "rowspan='{$subDataCount}'" : '';

        foreach($keys as $key)
        {
            echo "<tr data-key='{$row}'>";

            $index = 1;

            foreach($data as $field => $value)
            {
                if($field == 'sub_tables')
                {
                    foreach($value as $subModule => $subDatas)
                    {
                        if(isset($subDatas[$key]))
                        {
                            foreach($subDatas[$key] as $subField => $subValue)
                            {
                                echo '<td>';

                                $element = "dataList[{$row}][{$field}][{$subModule}][{$subRow}][{$subField}]";

                                echo $this->flow->buildControl($fields[$subModule][$subField], $subValue, $element);
                                if($index == 1) echo "<div id='error{$subRow}'></div>";

                                echo '</td>';

                                $index++;
                            }

                            $subRow++;
                        }
                    }
                }
                else
                {
                    if(isset($dataList[$key]))
                    {
                        echo "<td {$rowspan}>";

                        $element = "dataList[{$row}][{$field}]";

                        echo $this->flow->buildControl($fields[$flow->module][$field], $value, $element);
                        if($index == 1) echo "<div id='error{$row}'></div>";

                        echo '</td>';

                        $index++;
                    }
                }
            }

            echo '</tr>';
        }

        $row++;
    }
    ?>
  </tbody>
</table>
