<?php if($type == 'project' && common::hasPriv('doc', 'project2export')):?>
<?php $html = html::a($this->createLink('doc', 'project2export', "libID=$libID&docID=$docID", "html", true), "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export' id='project2export'");?>
<script>
$(function()
{
    $('#subHeader #pageActions .btn-toolbar').prepend(<?php echo json_encode($html);?>);
    $('#project2export').modalTrigger({type:'ajax', showHeader:false});
})
</script>
<?php endif; ?>
