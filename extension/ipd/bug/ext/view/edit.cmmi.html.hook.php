<?php
$html  = "<tr>";
$html .= "<th>" . $lang->bug->identify . "</th>";
$html .= "<td>";
$html .= html::select('identify', '', '', "class='form-control chosen'");
$html .= '</td>';
$html .= '</tr>';
?>
<script>
/**
 * Load identify.
 *
 * @access public
 * @return void
 */
function loadIdentify()
{
    var productID = $('#product').val();
    setTimeout(function()
    {
        var projectID = $('#project').val();
        var link = createLink('bug', 'ajaxGetIdentify', 'productID=' + productID + '&projectID=' + projectID + '&indentify=<?php echo $bug->identify; ?>');
        $.post(link, function(data)
        {
            $('#identify').replaceWith(data);
            $('#identify_chosen').remove();
            $('#identify').chosen();
        })
    }, 500);
}

$('#browser').closest('tr').after(<?php echo json_encode($html);?>);

$(document).on('change', '#product', loadIdentify);
$(document).on('change', '#project', loadIdentify);

loadIdentify();
</script>
