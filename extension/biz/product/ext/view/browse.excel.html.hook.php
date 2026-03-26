<?php $appTab = $this->app->tab;?>
<?php if($appTab == 'product' or ($appTab == 'project' and isset($project->hasProduct) && !$project->hasProduct)):?>
<?php
$linkHtml = '';
$openModule = $this->app->rawModule == 'projectstory' ? 'project' : 'product';
if((!empty($this->config->CRProduct) or $product->status != 'closed') and common::hasPriv('story', 'import')) $linkHtml = html::a($this->createLink('story', 'import', "productID=$productID&branch=$branch&type=$storyType&projectID=$projectID"), '<i class="icon-import muted"></i> <span class="text">' . $lang->story->import . '</span>', '', "class='btn btn-link import' data-toggle='modal' data-type='iframe' data-app='$openModule'");

$class = common::hasPriv('story', 'exportTemplate') ? '' : "class='disabled'";
$link  = common::hasPriv('story', 'exportTemplate') ? $this->createLink('story', 'exportTemplate', "productID=$productID&branch=$branch&type=$storyType") : '#';
$misc  = common::hasPriv('story', 'exportTemplate') ? "data-toggle='modal' data-type='iframe' class='exportTemplate' data-app='$openModule'" : "class='disabled'";
$exportHtml = "<li $class>" . html::a($link, $lang->story->exportTemplate, '', $misc) . '</li>';
?>
<script>
$(function()
{
    $('#exportActionMenu').closest('.btn-group').after(<?php echo json_encode($linkHtml)?>);
    $('#exportActionMenu').append(<?php echo json_encode($exportHtml)?>);
    $('.import').modalTrigger({width:650, type:'iframe'})
    $(".exportTemplate").modalTrigger({width:650, type:'iframe'});
    $('#exportActionMenu a.export').attr('href', createLink('story', 'export', "<?php echo "productID=$productID&orderBy=$orderBy&executionID=$projectID&browseType=$browseType&type=$storyType";?>"));
})
</script>
<?php endif;?>
