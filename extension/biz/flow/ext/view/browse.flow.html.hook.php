<?php
$queryBox = "<div class='cell" . ($mode == 'search' ? ' show' : '') . "' data-module='{$flow->module}' id='queryBox'></div>";

$designLink = $lang->flow->setSearch;
if(commonModel::hasPriv('workflowfield', 'setSearch')) $designLink = baseHTML::a($this->createLink('workflowfield', 'setSearch', "module={$flow->module}"), $lang->flow->setSearch, "target='_parent'");

$emptySearch  = "<li id='searchTab'>" . baseHTML::a('#emptySearchModal', "<i class='icon-search icon'></i>" . $lang->search->common, "data-toggle='modal'") . "</li>";
$emptySearch .= "
<div class='modal fade' id='emptySearchModal'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
        <h4 class='modal-title'>
          <span class='modal-title-name'>{$lang->search->common}</span>
        </h4>
      </div>
      <div class='modal-body'>
        <div class='alert'>" . sprintf($lang->flow->tips->emptySearchFields, $designLink) . "</div>
      </div>
    </div>
  </div>
</div>";
js::set('more', $lang->more);
?>
<script>
var moduleNavigator = '<?php echo $flow->navigator;?>';
var moduleApp       = '<?php echo $flow->app;?>';
$('.main-table').before(<?php echo json_encode($queryBox);?>);
$('#bysearchTab').remove();
$('#searchTab').remove();
if(mode == 'bysearch') $('#queryBox').addClass('show');
</script>
<?php
$featurebar = "<div id='featurebar'><ul class='nav'>";
$flowLabels = $this->dao->select('*')->from(TABLE_WORKFLOWLABEL)->where('module')->eq($flow->module)->orderBy('`order`')->fetchAll('id');
foreach($flowLabels as $flowLabel)
{
    if(!common::hasPriv($flow->module, $flowLabel->id)) continue;
    $class = ($mode == 'browse' && $flowLabel->id == $label) ? "class='active'" : '';
    $featurebar .= "<li id='browse{$flowLabel->id}' $class>" . baseHTML::a($this->createLink($flow->module, 'browse', "label={$flowLabel->id}"), $flowLabel->label) . '</li>';
}

if(common::hasPriv($flow->module, 'report'))
{
    $this->app->loadLang('workflowaction');
    $class = $this->methodName == 'report' ? "class='active'" : '';
    $featurebar .= "<li id='flowReport' $class>" . baseHTML::a($this->createLink($flow->module, 'report', "module={$flow->module}"), $this->lang->workflowaction->default->actions['report']) . '</li>';
}

/* Check search privilege. */
if(common::hasPriv($flow->module, 'search'))
{
    if(empty($this->config->{$flow->module}->search['fields']))
    {
        $featurebar .= $emptySearch;
    }
    else
    {
        $class       = $mode == 'search' ? "class='active'" : '';
        $featurebar .= "<li id='bysearch' $class><a class='btn btn-link querybox-toggle' id='bysearchTab'><i class='icon icon-search muted'></i> {$lang->flow->search}</a></li>";
    }
}

$featurebar .= '</ul></div>';
?>
<script>
$(function()
{
    $('#main .container').prepend(<?php echo json_encode($featurebar);?>);
    <?php if(strpos('|product|project|qa|', "|{$flow->app}|") !== false):?>
    $('#pageNav .angle-btn').each(function(i)
    {
        if(i > 0) $(this).hide();
    })
    <?php endif;?>

    if(mode == 'bysearch') $('#bysearch').addClass('active');
})
</script>
