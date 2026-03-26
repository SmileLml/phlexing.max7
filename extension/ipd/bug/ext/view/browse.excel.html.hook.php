<?php
$linkHtml = '';
if((!empty($this->config->CRProduct) or $product->status != 'closed') and common::hasPriv('bug', 'import'))
{
    $linkHtml = html::a($this->createLink('bug', 'import', "productID=$productID&branch=$branch"), '<i class="icon-import muted"></i> ' . $lang->bug->import, '', "class='btn btn-link import'");
}

$class = common::hasPriv('bug', 'exportTemplate') ? '' : "class='disabled'";
$link  = common::hasPriv('bug', 'exportTemplate') ? $this->createLink('bug', 'exportTemplate', "productID=$productID&branch=$branch") : '#';
$misc  = common::hasPriv('bug', 'exportTemplate') ? "class='exportTemplate'" : "class='disabled'";
$exportHtml = "<li $class>" . html::a($link, $lang->bug->exportTemplate, '', $misc) . '</li>';
?>
<style>
#mainMenu .btn-toolbar.pull-right .import {margin-left:0px;}
</style>
<script>
$(function(){
    $('#exportActionMenu').closest('.btn-group').after(<?php echo json_encode($linkHtml)?>);
    $('#exportActionMenu').append(<?php echo json_encode($exportHtml)?>);
    $(".import").modalTrigger({width:650, type:'iframe'});
    $(".exportTemplate").modalTrigger({width:650, type:'iframe'});
})
</script>
