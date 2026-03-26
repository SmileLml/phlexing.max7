<table class='table table-form'>
  <?php foreach($fields as $field):?>
  <?php if(!$field->show) continue;?>
  <?php $width = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');?>
  <tr>
    <th class='w-100px'><?php echo $field->name;?></th>
    <td>
      <div style='width: <?php echo $width;?>'>
        <?php echo $this->flow->buildControl($field, '', "data[$field->field]");?>
      </div>
    </td>
    <td></td>
  </tr>
  <?php endforeach;?>
  <tr>
    <th></th>
    <td class='form-actions'>
      <?php echo html::submitButton();?>
      <?php echo html::backButton();?>
      <?php echo html::hidden('dataIDList', implode(',', $dataList));?>
    </td>
  </tr>
</table>
