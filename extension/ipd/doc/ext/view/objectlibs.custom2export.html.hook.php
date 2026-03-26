<?php if($type == 'custom' && common::hasPriv('doc', 'custom2export')):?>
<?php $html = html::a($this->createLink('doc', 'custom2export', "libID=$libID&docID=$docID", 'html', true), "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export' id='custom2export'");?>
<script>
$(function()
{
    $('#subHeader #pageActions .btn-toolbar').prepend(<?php echo json_encode($html);?>);
    $('#custom2export').modalTrigger({type:'ajax', showHeader:false});
})
</script>
<?php endif; ?>
