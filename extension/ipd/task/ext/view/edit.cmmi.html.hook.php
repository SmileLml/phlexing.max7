<?php if($execution->type == 'stage'):?>
<?php
$html  = '<tr>';
$html .= '<th>';
$html .= $lang->task->design;
$html .= '</th>';
$html .= '<td>';
$html .= html::select('design', '', '', "class='form-control chosen'");
$html .= '</td>';
$html .= '<tr>';

js::set('designID', $task->design);
?>
<script>
$(function()
{
    $('#story').change(function(){setStoryDesign(designID);});

    $('#story').closest('tr').after(<?php echo json_encode($html);?>);
    $('#design').chosen();
    $('#story').change();
})
</script>
<?php endif;?>

<?php js::set('attribute', $execution->attribute);?>
<script>
$(function()
{
    if(attribute == 'request' || attribute == 'review') $('#story').closest('tr').hide();

    $('#execution').change(function()
    {
        link = createLink('execution', 'ajaxGetAttribute', "executionID=" + $('#execution').val());
        $.get(link, function(attribute)
        {
            if(attribute == 'request' || attribute == 'review')
            {
                $('#story').closest('tr').hide();
            }
            else
            {
                $('#story').closest('tr').show();
            }
        });
        setStoryDesign(designID);
    })
})
</script>
