<?php if($type == 'execution' && common::hasPriv('doc', 'execution2export')):?>
<?php $html = html::a($this->createLink('doc', 'execution2export', "libID=$libID&docID=$docID", 'html', true), "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export' id='execution2export'");?>
<script>
$(function()
{
    $('#subHeader #pageActions .btn-toolbar').prepend(<?php echo json_encode($html);?>);
    $('#execution2export').modalTrigger({type:'ajax', showHeader:false});
})
</script>
<?php endif; ?>