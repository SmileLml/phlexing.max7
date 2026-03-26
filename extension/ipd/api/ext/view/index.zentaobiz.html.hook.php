<?php if(!empty($apiID) and common::hasPriv('api', 'export')):?>
<?php $html = html::a($this->createLink('api', 'export', "api=$apiID&version=$version&release=$release", 'html', true), '<i class="icon-export muted"> </i>' . $lang->export, '', "class='btn btn-link export' id='apiExport'");?>
<script>
$(function()
{
    $('#subHeader #pageActions .btn-toolbar').prepend(<?php echo json_encode($html);?>);
    $('#apiExport').modalTrigger({type:'ajax', showHeader:false});
})
</script>
<?php endif;?>
