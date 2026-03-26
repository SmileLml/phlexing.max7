<?php if($type == 'book' && common::hasPriv('doc', 'wiki2export')):?>
<?php $html = html::a($this->createLink('doc', 'wiki2export', "libID=$libID&docID=$docID", "html", true), "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export' id='wiki2export'");?>
<script>
$(function()
{
    $('#subHeader #pageActions .btn-toolbar').prepend(<?php echo json_encode($html);?>);
    $('#wiki2export').modalTrigger({type:'ajax', showHeader:false});
})
</script>
<?php endif; ?>