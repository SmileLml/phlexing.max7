<?php $this->app->loadLang('file');?>
<div id='exportReport' class='modal-dialog'>
  <div class='modal-content'>
    <div class='modal-header'><div class='modal-title'><?php echo $lang->export;?></div></div>
    <div class='modal-actions'><button class='ghost btn square' data-dismiss='modal' type='button'><span class='close'></span></button></div>
    <div class='modal-body'>
      <form method='post' onsubmit='setDownloading()' action='<?php echo $this->createLink('report', 'export', "module=$module&productID=$productID&taskID=$taskID")?>' target='_self' style="padding: 10px 0px 10px" id='exportForm'>
        <table class="w-p100 table-form">
          <tr>
            <th class="w-110px"><?php echo $lang->setFileName?></th>
            <td class="w-150px"><input type="text" name="fileName" id="fileName" class="form-control"></td>
            <td><?php echo html::submitButton($lang->save, '', 'btn primary upload-btn') . html::hidden('items') . html::hidden('datas');?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script>
$(function()
{
    $(document).on('click', '#exportForm #submit', function()
    {
        if($('#datas').length == 0) return true;

        $('[name^=charts]').each(function()
        {
            items = $('#exportForm #items').val();
            if($(this).prop('checked')) items += $(this).val() + ',';
            $('#exportForm #items').val(items);
        });

        var dataBox    = "<input type='hidden' name='%name%' id='%id%' />";
        var canvasSize = $('.chart canvas').length;
        if(canvasSize == 0)
        {
            alert('<?php echo $lang->report->errorNoChart?>');
            return false;
        }
        $('.tab-pane.active .chart canvas').each(function(i)
        {
            var canvas  = this;
            var $canvas = $(canvas);

            if(typeof(canvas.toDataURL) == 'undefined')
            {
                alert('<?php echo $lang->report->errorExportChart?>');
                return false;
            }
            var dataURL     = canvas.toDataURL("image/png");
            var dataID      = $canvas.parents('.chart').attr('id');
            var thisDataBox = dataBox.replace('%name%', dataID);
            thisDataBox     = thisDataBox.replace('%id%', dataID);
            $('#exportForm #submit').after(thisDataBox);
            $('#exportForm #' + dataID).val(dataURL);

            if(i == canvasSize - 1) $('#datas').remove();
        });
    });
})

function setDownloading()
{
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;   // Opera don't support, omit it.

    var $fileName = $('#fileName');
    if($fileName.val() === '') $fileName.val('<?php echo $lang->file->untitled;?>');

    $.cookie.set('downloading', 0);
    $('.upload-btn').attr('disabled', 'disabled').addClass('disabled loading');
    time = setInterval("closeWindow()", 300);
    return true;
}

function closeWindow()
{
    if($.cookie.get('downloading') == 1)
    {
        $.cookie.set('downloading', null);
        clearInterval(time);
        $('#exportReport .modal-actions span.close').trigger('click');
    }
}
</script>
