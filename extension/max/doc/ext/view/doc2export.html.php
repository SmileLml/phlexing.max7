<?php include '../../../common/view/header.modal.html.php';?>
<?php $this->app->loadLang('file');?>
<form class="main-form" target='hiddenwin' method='post'>
  <table class='table table-form table-borderless'>
    <tr>
      <th class='w-100px'><?php echo $lang->doc->export->fileName?></th>
      <td><?php echo html::input('fileName', $data->name, "class='form-control'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->doc->export->range; ?></th>
      <td><?php echo html::select('range', $chapters, 0, "class='form-control chosen'")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->doc->export->fileType?></th>
      <td><?php echo html::select('fileType', array('word'), 0, "class='form-control' disabled")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->doc->export->encode?></th>
      <td><?php echo html::select('encode', array('UTF-8'), 0, "class='form-control' disabled")?></td>
    </tr>
    <tr>
      <th><?php echo $lang->doc->export->format?></th>
      <td><?php echo html::radio('format', $lang->doc->export->formatList, 'zip');?></td>
    </tr>
    <tr>
      <td colspan='2' class='text-center'><?php echo html::submitButton($lang->export, "onclick='setDownloading();'");?></td>
    </tr>
  </table>
</form>
<style>
.table-borderless > tbody > tr{border-bottom: none;}
.table-borderless > tbody > tr > th{width: 90px; text-align: right;}
</style>
<script>
function setDownloading()
{
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;   // Opera don't support, omit it.
    var $fileName = $('#fileName');
    if($fileName.val() === '') $fileName.val('<?php echo $lang->file->untitled;?>');
    $.cookie.set('downloading', 0);
    time = setInterval("closeWindow()", 300);
    $('#mainContent').addClass('loading');
    return true;
}

function closeWindow()
{
    if($.cookie.get('downloading') == 1)
    {
        $('#mainContent').removeClass('loading');
        $.closeModal();
        $.cookie.set('downloading', null);
        clearInterval(time);
    }
}

$(function()
{
    setTimeout(function()
    {
        if($.cookie.get('checkedItem') !== '') $('#range').val('selected').trigger('chosen:updated');
    }, 600);
})
</script>
<iframe name='hiddenwin' class='hidden'></iframe>
