<?php include '../../../common/view/header.modal.html.php';?>
<?php $this->app->loadLang('file');?>
<div id="mainContent" class='load-indicator'>
  <div class='main-header'>
    <h2><?php echo $lang->export;?></h2>
  </div>
  <form class="main-form" target='hiddenwin' method='post'>
    <table class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->doc->export->fileName?></th>
        <td><?php echo html::input('fileName', $fileName, "class='form-control'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->doc->export->range; ?></th>
        <td><?php echo html::select('range', $this->lang->doc->exportFilesRanger, 'all', "class='form-control chosen'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->doc->export->encode?></th>
        <td><?php echo html::select('encode', array('UTF-8'), 0, "class='form-control' disabled")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->doc->export->format?></th>
        <td><?php echo html::radio('format', array('zip' => $lang->doc->zip), 'zip');?></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center'><?php echo html::submitButton($lang->export, "onclick='setDownloading();'");?></td>
      </tr>
    </table>
  </form>
</div>
<script>
function setDownloading()
{
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;   // Opera don't support, omit it.
    var $fileName = $('#fileName');
    if($fileName.val() === '') $fileName.val('<?php echo $lang->file->untitled;?>');
    $.cookie('downloading', 0);
    time = setInterval("closeWindow()", 300);
    $('#mainContent').addClass('loading');
    return true;
}

function closeWindow()
{
    if($.cookie('downloading') == 1)
    {
        $('#mainContent').removeClass('loading');
        $.closeModal();
        $.cookie('downloading', null);
        clearInterval(time);
    }
}

$(function()
{
    var checkedItem = $.cookie('checkedItem');
    if(checkedItem != null)
    {
        $('#range').val('selected');
        $('#range').trigger('chosen:updated');
    }
    else
    {
        $('#range').val('all');
        $('#range').trigger('chosen:updated');
    }

});
</script>
<iframe name='hiddenwin' class='hidden'></iframe>
<?php include '../../../common/view/footer.modal.html.php';?>
