<?php $taskSubStatus = $this->loadModel('workflowfield')->getByField('task', 'subStatus');?>
<?php $bugSubStatus  = $this->loadModel('workflowfield')->getByField('bug',  'subStatus');?>
<?php if((!empty($taskSubStatus->options) or !empty($bugSubStatus->options)) && common::hasPriv('execution', 'setLaneFields')):?>
<?php
if(empty($taskSubStatus->options)) unset($lang->kanbanSetting->modeList['task']);
if(empty($bugSubStatus->options))  unset($lang->kanbanSetting->modeList['bug']);

$laneField  = html::radio('laneField', $lang->kanbanSetting->laneFields, $setting->laneField);
$kanbanMode = html::radio('mode', $lang->kanbanSetting->modeList, 'task');

/* Add trs of laneField and mode. */
$settings = <<<EOT
    <tr>
      <th class='text-right w-200px'>{$lang->kanbanSetting->laneField}</th>
      <td>{$laneField}</td>
    </tr>
    <tr class='subStatusTR hide'>
      <th>{$lang->kanbanSetting->mode}</th>
      <td>
        {$kanbanMode}
        <span class='subStatusTips text-important hide'>{$lang->kanbanSetting->subStatusTips}</span>
      </td>
    </tr>
EOT;

/* Loop modeList to add trs of sub status and colors. */
foreach($lang->kanbanSetting->modeList as $modeCode => $modeName)
{
    $field = ${$modeCode . 'SubStatus'};

    if(!empty($field->options))
    {
        $subStatus = html::checkbox("subStatus[$modeCode]", $field->options, isset($setting->subStatus[$modeCode]) ? $setting->subStatus[$modeCode] : array_keys($field->options), '', 'type=inline');

        $colors = '';
        foreach($field->options as $status => $statusName)
        {
            $color   = isset($setting->subStatusColor[$modeCode][$status]) ? $setting->subStatusColor[$modeCode][$status] : '';
            $colors .= <<<EOT
    <div class='col-sm-2'>
      <div class='input-group'>
        <input type='hidden' id='subStatusColor{$modeCode}{$status}' name="subStatusColor[$modeCode][{$status}]" data-provide='colorpicker' data-wrapper='input-group-btn' value='{$color}' data-colors='#333,#2B529C,#E48600,#D2323D,#229F24,#777,#D2691E,#008B8B,#2E8B57,#4169E1,#4B0082,#FA8072,#BA55D3,#2E8B57,#6B8E23'>
        <div class='input-group-cell'>{$statusName}</div>
      </div>
    </div>
EOT;
        }

        $settings .= <<<EOT
    <tr class='subStatusTR hide' data-mode='{$modeCode}'>
      <th>{$lang->kanbanSetting->subStatus}</th>
      <td><div class='row'>{$subStatus}</div></td>
    </tr>
    <tr class='subStatusTR hide' data-mode='{$modeCode}'>
      <th class='text-right'>{$lang->execution->kanbanColsColor}</th>
      <td><div class='row'>{$colors}</div></td>
    </tr>
EOT;
    }
}
?>
<script>
$('form .table-form tbody').prepend(<?php echo json_encode($settings);?>);

/* Toggle display of colors according to checked sub status. */
$('input[type=checkbox][name^=subStatus]').change(function()
{
    var mode      = $(this).parents('tr').attr('data-mode');
    var subStatus = $(this).val();
    $('#subStatusColor' + mode + subStatus).parents('.col-sm-2').toggle($(this).prop('checked'));
});

$('[name=mode]').change(function()
{
    var mode = $('[name=mode]:checked').val();
    $('[data-mode=task], [data-mode=bug]').hide();  // Hide all tr of the sub status.
    $('[data-mode=' + mode + ']').show();           // Show all tr of the sub status according to the selected mode.

    $('input[type=checkbox][name^=subStatus]').change();    // Toggle display of colors.
});

$('[name=laneField]').change(function()
{
    var laneField = $('[name=laneField]:checked').val();

    if(laneField == 'status')
    {
        $('.statusTR').show();
        $('.subStatusTR').hide();   // Hide all tr of the sub status.
        $('.subStatusTips').hide();
    }
    else
    {
        $('.statusTR').hide();
        $('.subStatusTips').show();
        $('[name=mode]').parents('tr').show();  // Show tr of mode.
        $('[name=mode]').change();              // Toggle display of all tr of the sub status according to the selected mode.
    }
});
$('[name=laneField]').change();
</script>
<?php endif;?>
