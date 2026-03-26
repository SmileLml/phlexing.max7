<table class='table table-data'>
  <?php foreach($blockFields as $field):?>
  <?php
  if(!$field->show) continue;
  if(isset($childFields[$field->field])) continue;

  /* Display data of the prev flow. */
  $attr     = '';
  $link     = '';
  $relation = zget($relations, $field->field, '');
  if($relation && strpos(",$relation->actions,", ',many2one,') === false)
  {
      $prevDataID = isset($data->{$field->field}) ? $data->{$field->field} : 0;
      $attr       = "class='prevTR' data-prev='{$relation->prev}' data-next='{$relation->next}' data-action='$flowAction->action' data-field='{$relation->field}' data-dataID='$prevDataID'";

      if(commonModel::hasPriv($relation->prev, 'view')) $link = $this->createLink($relation->prev, 'view', "dataID=$prevDataID");
  }
  ?>
  <tr <?php echo $attr;?>>
    <th class='w-80px'><?php echo $field->name;?></th>
    <td>
      <?php 
      if($field->control == 'file')
      {
          $filesName = "{$field->field}files";
          echo $this->fetch('file', 'printFiles', array('files' => $data->{$filesName}, 'fieldset' => 'false'));
      }
      elseif(is_array($data->{$field->field}))
      {
          foreach($data->{$field->field} as $value) echo zget($field->options, $value) . ' ';
      }
      else
      {
          if(strpos(',date,datetime,', ",$field->control,") !== false)
          {
              echo formatTime($data->{$field->field});
          }
          else
          {
              $fieldValue = zget($field->options, $data->{$field->field});
              echo $link ? baseHTML::a($link, $fieldValue) : $fieldValue;
          }
      }
      ?>
    </td>
  </tr>
  <?php endforeach;?>
</table>
