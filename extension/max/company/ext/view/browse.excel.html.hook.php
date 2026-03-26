<?php
$class      = common::hasPriv('user', 'exportTemplate') ? '' : "class='disabled'";
$link       = common::hasPriv('user', 'exportTemplate') ? $this->createLink('user', 'exportTemplate') : '#';
$misc       = common::hasPriv('user', 'exportTemplate') ? "class='exportTemplate'" : "class='disabled'";
$templetBtn = "<li $class>" . html::a($link, $lang->user->exportTemplate, '', $misc) . '</li>';

$class     = common::hasPriv('user', 'export') ? "" : "class='disabled'";
$misc      = common::hasPriv('user', 'export') ? "class='export'" : "class='disabled'";
$link      = common::hasPriv('user', 'export') ? $this->createLink('user', 'export', "browseType=$browseType&param=$param&type=$type&orderBy=$orderBy") : '#';
$exportBtn = "<li $class>" . html::a($link, $lang->user->export, '', $misc) . "</li>";

$exportHtml  = "<div class='btn-group dropdown-hover'>";
$exportHtml .= "<button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown'>";
$exportHtml .= '<i class="icon icon-export muted"></i> <span class="text">' . $lang->export . '</span> <span class="caret"></span></button>';
$exportHtml .= '</button>';
$exportHtml .= "<ul class='dropdown-menu' id='exportActionMenu'>";
$exportHtml .= $exportBtn;
$exportHtml .= $templetBtn;
$exportHtml .= '</ul>';
$exportHtml .= '</div>';

$importHtml = common::buildIconButton('user', 'import', '', '', 'button', 'import', '', 'import');
?>
<script>
    $('#mainMenu .pull-right').prepend(<?php echo json_encode($importHtml);?>);
    $('#mainMenu .pull-right').prepend(<?php echo json_encode($exportHtml);?>);
    $(".import").modalTrigger({width:650, type:'iframe'});
    $(".export").modalTrigger({width:650, type:'iframe', shown: setCheckedCookie});
    $(".exportTemplate").modalTrigger({width:650, type:'iframe', className: 'export_template'});
</script>
