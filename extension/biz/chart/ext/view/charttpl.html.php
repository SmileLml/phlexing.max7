<?php
$field       = $thSetting['field'];
$required    = $thSetting['required'] ? 'required' : '';
$options     = $thSetting['options'] == 'field' ? array('' => '') + $fieldList : $this->lang->chart->{$thSetting['options']};
$placeholder = $thSetting['placeholder'];
/* Pie group and other xaxis have dateGroup. */
if($chartType == 'pie')
{
    if($field == 'group')    $default = isset($defaultValues['group'][0]['field'])  ? $defaultValues['group'][0]['field']  : '';
    if($field == 'metric')   $default = isset($defaultValues['metric'][0]['field']) ? $defaultValues['metric'][0]['field'] : '';
    if($field == 'valOrAgg') $default = isset($defaultValues['metric'][0]['valOrAgg']) ? $defaultValues['metric'][0]['valOrAgg'] : '';
}
else
{
    if($field == 'xaxis') $default = isset($defaultValues['xaxis'][0]['field']) ? $defaultValues['xaxis'][0]['field'] : '';
    if($isMultiCol)
    {
        if($field == 'yaxis')    $default = isset($defaultValues['yaxis'][$index]['field']) ? $defaultValues['yaxis'][$index]['field'] : '';
        if($field == 'valOrAgg') $default = isset($defaultValues['yaxis'][$index]['valOrAgg']) ? $defaultValues['yaxis'][$index]['valOrAgg'] : '';
    }
}

if(!is_string($default)) $default = '';
?>
<td colspan='<?php echo $thSetting['col']?>'>
  <?php echo html::select($isMultiCol ? $field . '[]' : $field, $options, $default, "class='form-control " . ($isMultiCol ? "multi-$field" : '') . " picker-select $required' $required onchange='pieChange(this.value, \"$field\", true, " . ($isMultiCol ? '1' : '0') . ")' data-placeholder='$placeholder'");?>
</td>
