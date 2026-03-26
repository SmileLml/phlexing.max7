<?php if($type == 'product' && common::hasPriv('doc', 'product2export')):?>
<?php $html = html::a($this->createLink('doc', 'product2export', "libID=$libID&docID=$docID", 'html', true), "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export' id='product2export'");?>
<script>
$(function()
{
    $('#subHeader #pageActions .btn-toolbar').prepend(<?php echo json_encode($html);?>);
    $('#product2export').modalTrigger({type:'ajax', showHeader:false});
})
</script>
<?php endif; ?>