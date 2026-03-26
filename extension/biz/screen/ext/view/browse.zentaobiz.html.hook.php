<div id="mainMenu" class="clearfix hide">
  <div class="btn-toolbar pull-right">
    <?php common::printLink('screen', 'create', "dimensionID=$dimension", "<i class='icon icon-plus'></i> " . $lang->screen->create, '', "class='btn btn-primary iframe' data-width='600'" , '', true);?>
  </div>
</div>

<div id="dropdown" class='hide'>
  <?php if(common::hasPriv('screen', 'edit') || common::hasPriv('screen', 'design') || common::hasPriv('screen', 'delete')):?>
  <div class="actions" style="visibility: visible;">
    <div class="dropdown">
      <a href="javascript:;" data-toggle="context-dropdown" class="btn btn-link"><i class="icon icon-ellipsis-v"></i></a>
      <ul class="dropdown-menu">
        <li>
          <?php common::printLink('screen', 'design', 'screenID=%s', "<i class='icon icon-design'></i>" . $lang->screen->design, '', "", '');?>
          <?php common::printLink('screen', 'edit',   'screenID=%s', "<i class='icon icon-edit'></i>"   . $lang->screen->edit  , '', "class='iframe' data-width='600'", '', true);?>
          <?php common::printLink('screen', 'delete', 'screenID=%s', "<i class='icon icon-trash'></i>"  . $lang->screen->delete, '', "target='hiddenwin'");?>
        </li>
      </ul>
    </div>
  </div>
  <?php endif;?>
</div>

<div id="draftLabel" class='hide'>
  <label class='label label-badge label-draft'><?php echo $lang->screen->draft;?></label>
</div>

<script>
$('#mainContent').before($('#mainMenu').prop('outerHTML'));
$('#main #mainMenu').show();

$('.screen-title').each(function() 
{
    $(this).prop('outerHTML', "<div class='bottom-top'>" + $(this).prop('outerHTML') + '</div>');
})
$('.screen .bottom[data-status=draft] .screen-title').after($('#draftLabel').html());

$('.screen .bottom[data-builtin=0]').prepend($('#dropdown').html());
$('.screen .dropdown a').each(function()
{
    id = $(this).closest('.col-md-3').data('id');
    $(this).attr('href', $(this).attr('href').replace('%s', id));
})

/* Hide contextmenu when page scroll */
$(window).on('scroll', function()
{
    $.zui.ContextMenu.hide();
});
</script>
<style>
.screen .bottom {height: 80px;}
.screen .bottom .actions {position: absolute; right: 5px; top: 185px;}
.screen .bottom .bottom-top {display: flex; margin-right: 20px;}
.screen .bottom .label-draft {background: #8166ee1f !important; color: #8166ee !important; min-width: 40px; margin-left: 12px;}
</style>
