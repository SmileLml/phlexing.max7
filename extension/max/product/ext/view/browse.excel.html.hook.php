<?php if(!empty($productID) && !($this->app->tab == 'project' && $storyType == 'requirement')):?>
<?php
$openModule = $this->app->rawModule == 'projectstory' ? 'project' : 'product';
$linkHtml   = "<div class='btn-group'><button type='button' class='btn btn-link' data-toggle='dropdown' id='importAction'><i class='icon icon-import muted'></i><span class='text'>{$lang->import}</span><span class='caret'></span></button><ul class='dropdown-menu pull-right' id='importActionMenu'>";

if($this->app->tab == 'product')
{
    $canImport = ((!empty($this->config->CRProduct) or $product->status != 'closed') and common::hasPriv('story', 'import'));
    $class     = $canImport ? '' : "class='disabled'";
    $link      = $canImport ? $this->createLink('story', 'import', "productID=$productID&branch=$branch&storyType=$storyType") : '#';
    $misc      = $canImport ? "class='btn btn-link import' data-toggle='modal' data-type='iframe' data-app='$openModule'" : "class='disabled'";
    $linkHtml .= "<li $class>" . html::a($link, $lang->story->import, '', $misc) . "</li>";
}

if($this->app->tab == 'project')
{
    $class     = common::hasPriv('projectstory', 'importFromLib') ? '' : "class=disabled";
    $link      = common::hasPriv('projectstory', 'importFromLib') ? $this->createLink('projectstory', 'importFromLib', "projectID=$projectID&productID=$productID&libID=0&storyType=$storyType") : '#';
    $misc      = common::hasPriv('projectstory', 'importFromLib') ? "data-app='{$this->app->tab}'" : "class=disabled";
    $linkHtml .= "<li $class>" . html::a($link, $lang->projectstory->importFromLib, '', $misc) . "</li>";
}

$linkHtml .= "</ul></div>";

$class = common::hasPriv('story', 'exportTemplate') ? '' : "class='disabled'";
$link  = common::hasPriv('story', 'exportTemplate') ? $this->createLink('story', 'exportTemplate', "productID=$productID&branch=$branch&storyType=$storyType") : '#';
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
    $('#exportActionMenu a.export').attr('href', createLink('story', 'export', "<?php echo "productID=$productID&orderBy=$orderBy&executionID=0&browseType=$browseType&storyType=$storyType";?>"));
})
</script>
<?php endif;?>
