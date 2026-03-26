<?php
$inIframe = isonlybody() ? '' : 'iframe';

$html = '';
if($story->source == 'meeting' and !empty($story->sourceNote))
{
    $meeting = $this->loadModel('meeting')->getByID($story->sourceNote);
    $html    = "<th>{$lang->story->meeting}</th>";
    $html   .= '<td>';
    $html   .= html::a($this->createLink('meeting', 'view', "meetingID=$meeting->id", '', true), $meeting->name, '', "class='$inIframe'");
    $html   .= '</td>';
}
elseif($story->source == 'researchreport' and !empty($story->sourceNote))
{
    $report  = $this->loadModel('researchreport')->getByID($story->sourceNote);
    $html    = "<th>{$lang->story->researchreport}</th>";
    $html   .= '<td>';
    $html   .= html::a($this->createLink('researchreport', 'view', "reportID=$report->id", '', true), $report->title, '', "class='$inIframe'");
    $html   .= '</td>';
}
?>
<?php if(!empty($html)):?>
<script>
$('#sourceNoteBox').html(<?php echo json_encode($html);?>);
</script>
<?php endif;?>
