<?php
$field       = $thSetting['field'];
$required    = $thSetting['required'] ? 'required' : '';
$options     = $thSetting['options'] == 'field' ? arrayUnion(array('' => ''), $fieldList) : $this->lang->pivot->{$thSetting['options']};
$placeholder = $thSetting['placeholder'];
$default     = isset($defaultValues[$field]) ? $defaultValues[$field] : '';

if($isMultiCol) $default = isset($default[$index]) ? $default[$index] : '';

/* If the saved pivot type is not the type of the current type form, no value is echoed.*/
/* 如果formSettings是数组并且当前图表的类型是数据库里存的图表类型，回显数据。*/
if(is_array($formSettings) and $pivot->type == $pivotType)
{
    if(isset($formSettings[0][$field]))
    {
        if(is_array($formSettings[0][$field]))
        {
            $default = $formSettings[0][$field][$i];
        }
        else
        {
            $default = $formSettings[0][$field];
        }
    }
}
if(!is_string($default)) $default = '';
?>
<td colspan='<?php echo $thSetting['col']?>'>
  <?php echo html::select($isMultiCol ? $field . '[]' : $field, $options, $default, "class='form-control " . ($isMultiCol ? "multi-$field" : '') . " chosen $required' $required onchange='pieChange(this.value, \"$field\", true, " . ($isMultiCol ? '1' : '0') . ")' data-placeholder='$placeholder'");?>
</td>
