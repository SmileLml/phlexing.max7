<?php
$class      = common::hasPriv('ticket', 'exportTemplate') ? '' : "class='disabled'";
$link       = common::hasPriv('ticket', 'exportTemplate') ? $this->createLink('ticket', 'exportTemplate') : '#';
$misc       = common::hasPriv('ticket', 'exportTemplate') ? "class='exportTemplate'" : "class='disabled'";
$templetBtn = "<li $class>" . html::a($link, $lang->ticket->exportTemplate, '', $misc) . '</li>';

$class     = common::hasPriv('ticket', 'export') ? "" : "class='disabled'";
$misc      = common::hasPriv('ticket', 'export') ? "class='export'" : "class='disabled'";
$link      = common::hasPriv('ticket', 'export') ? $this->createLink('ticket', 'export', "browseType=$browseType&orderBy=$orderBy") : '#';
$exportBtn = "<li $class>" . html::a($link, $lang->ticket->exportData, '', $misc) . "</li>";

if(common::hasPriv('ticket', 'exportTemplate'))
{
    $exportHtml  = "<div class='btn-group dropdown-hover'>";
    $exportHtml .= "<button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown'>";
    $exportHtml .= '<i class="icon icon-export muted"></i> <span class="text">' . $lang->export . '</span> <span class="caret"></span></button>';
    $exportHtml .= '</button>';
    $exportHtml .= "<ul class='dropdown-menu' id='exportActionMenu'>";
    $exportHtml .= $exportBtn;
    $exportHtml .= $templetBtn;
    $exportHtml .= '</ul>';
    $exportHtml .= '</div>';
}
else
{
    $exportHtml = '';
}

$importHtml = common::buildIconButton('ticket', 'import', '', '', 'button', 'import muted', '', 'import');
?>
<style>
#mainMenu .pull-right > .btn-group + .btn {margin-left: -5px;}
</style>
<script>
$('#mainMenu .pull-right .export').remove();
$('#mainMenu .pull-right').prepend(<?php echo json_encode($importHtml);?>);
$('#mainMenu .pull-right').prepend(<?php echo json_encode($exportHtml);?>);
$(".import").modalTrigger({width:650, type:'iframe'});
$(".export").modalTrigger({width:650, type:'iframe', shown: setCheckedCookie});
$(".exportTemplate").modalTrigger({width:650, type:'iframe', className: 'export_template'});
</script>
